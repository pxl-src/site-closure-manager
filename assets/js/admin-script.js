jQuery(document).ready(function($) {
    'use strict';
    
    // Initialiser les color pickers
    $('.scm-color-picker').wpColorPicker();
    
    // Toggle des liens sociaux
    $('#show_social').on('change', function() {
        if ($(this).is(':checked')) {
            $('#social-links-section').slideDown();
        } else {
            $('#social-links-section').slideUp();
        }
    });
    
    // Upload de logo
    var mediaUploader;
    
    $('#upload-logo-btn').on('click', function(e) {
        e.preventDefault();
        
        // Si l'uploader existe déjà, l'ouvrir
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        // Créer l'uploader
        mediaUploader = wp.media({
            title: 'Choisir un logo',
            button: {
                text: 'Utiliser cette image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        // Quand une image est sélectionnée
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#logo_url').val(attachment.url);
            
            // Mettre à jour ou créer la prévisualisation
            if ($('.scm-logo-preview').length) {
                $('.scm-logo-preview img').attr('src', attachment.url);
            } else {
                var preview = '<div class="scm-logo-preview"><img src="' + attachment.url + '" alt="Logo preview"></div>';
                $('.scm-media-wrapper').after(preview);
            }
            
            // Afficher le bouton de suppression
            if (!$('#remove-logo-btn').length) {
                var removeBtn = '<button type="button" class="button" id="remove-logo-btn">Supprimer</button>';
                $('#upload-logo-btn').after(removeBtn);
            }
        });
        
        mediaUploader.open();
    });
    
    // Supprimer le logo
    $(document).on('click', '#remove-logo-btn', function(e) {
        e.preventDefault();
        $('#logo_url').val('');
        $('.scm-logo-preview').remove();
        $(this).remove();
    });
    
    // Validation du formulaire
    function validateForm() {
        var isValid = true;
        var errorMessages = [];
        
        // Vérifier que la date de fermeture est définie
        if ($('#scm-enabled').is(':checked')) {
            if (!$('#closure_date').val()) {
                isValid = false;
                errorMessages.push('La date de fermeture est obligatoire');
                $('#closure_date').css('border-color', '#d63638');
            } else {
                $('#closure_date').css('border-color', '');
            }
            
            // Vérifier que le titre est rempli
            if (!$('#page_title').val().trim()) {
                isValid = false;
                errorMessages.push('Le titre de la page est obligatoire');
                $('#page_title').css('border-color', '#d63638');
            } else {
                $('#page_title').css('border-color', '');
            }
            
            // Vérifier que le message est rempli
            if (!$('#page_message').val().trim()) {
                isValid = false;
                errorMessages.push('Le message est obligatoire');
                $('#page_message').css('border-color', '#d63638');
            } else {
                $('#page_message').css('border-color', '');
            }
            
            // Vérifier que la date de réouverture est après la date de fermeture
            if ($('#reopen_date').val()) {
                var closureDate = new Date($('#closure_date').val() + ' ' + $('#closure_time').val());
                var reopenDate = new Date($('#reopen_date').val() + ' ' + $('#reopen_time').val());
                
                if (reopenDate <= closureDate) {
                    isValid = false;
                    errorMessages.push('La date de réouverture doit être après la date de fermeture');
                    $('#reopen_date').css('border-color', '#d63638');
                } else {
                    $('#reopen_date').css('border-color', '');
                }
            }
        }
        
        if (!isValid) {
            showError(errorMessages.join('<br>'));
        }
        
        return isValid;
    }
    
    // Soumettre le formulaire via AJAX
    $('#scm-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        // Valider le formulaire
        if (!validateForm()) {
            return false;
        }
        
        // Afficher un loader
        var $submitBtn = $(this).find('button[type="submit"]');
        var originalText = $submitBtn.html();
        $submitBtn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> Enregistrement...');
        
        // Récupérer les rôles sélectionnés
        var bypassRoles = [];
        $('input[name="bypass_roles[]"]:checked').each(function() {
            bypassRoles.push($(this).val());
        });
        
        // Préparer les données
        var formData = {
            action: 'scm_save_settings',
            nonce: scmAjax.nonce,
            enabled: $('#scm-enabled').is(':checked'),
            closure_date: $('#closure_date').val(),
            closure_time: $('#closure_time').val(),
            reopen_date: $('#reopen_date').val(),
            reopen_time: $('#reopen_time').val(),
            page_title: $('#page_title').val(),
            page_message: $('#page_message').val(),
            show_countdown: $('#show_countdown').is(':checked'),
            background_color: $('#background_color').val(),
            text_color: $('#text_color').val(),
            accent_color: $('#accent_color').val(),
            logo_url: $('#logo_url').val(),
            contact_email: $('#contact_email').val(),
            bypass_roles: bypassRoles,
            show_social: $('#show_social').is(':checked'),
            facebook_url: $('#facebook_url').val(),
            twitter_url: $('#twitter_url').val(),
            instagram_url: $('#instagram_url').val(),
            linkedin_url: $('#linkedin_url').val()
        };
        
        // Envoyer la requête AJAX
        $.ajax({
            url: scmAjax.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                $submitBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    showSuccess(response.data);
                    
                    // Recharger la page après 1 seconde pour mettre à jour le statut
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showError(response.data);
                }
            },
            error: function(xhr, status, error) {
                $submitBtn.prop('disabled', false).html(originalText);
                showError('Une erreur est survenue lors de la sauvegarde : ' + error);
            }
        });
    });
    
    // Afficher un message de succès
    function showSuccess(message) {
        var $successMsg = $('#scm-save-message');
        $successMsg.find('p').html('<strong>' + message + '</strong>');
        $successMsg.slideDown();
        
        setTimeout(function() {
            $successMsg.slideUp();
        }, 5000);
    }
    
    // Afficher un message d'erreur
    function showError(message) {
        var $errorMsg = $('#scm-error-message');
        $errorMsg.find('#error-text').html(message);
        $errorMsg.slideDown();
        
        setTimeout(function() {
            $errorMsg.slideUp();
        }, 8000);
    }
    
    // Animation du spinner
    $('<style>.spin { animation: spin 1s linear infinite; } @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>').appendTo('head');
    
    // Confirmation avant activation
    $('#scm-enabled').on('change', function() {
        if ($(this).is(':checked')) {
            if (!confirm('Êtes-vous sûr de vouloir activer la fermeture du site ? Les visiteurs verront la page de maintenance selon les dates définies.')) {
                $(this).prop('checked', false);
            }
        }
    });
    
    // Prévisualisation en temps réel des couleurs
    $('.scm-color-picker').on('change', function() {
        var color = $(this).val();
        var colorName = $(this).attr('name');
        console.log('Couleur ' + colorName + ' changée en : ' + color);
    });
    
    // Afficher/masquer les détails selon la date de réouverture
    $('#reopen_date').on('change', function() {
        if ($(this).val()) {
            $('#show_countdown').closest('.scm-form-group').slideDown();
        } else {
            $('#show_countdown').prop('checked', false);
            $('#show_countdown').closest('.scm-form-group').slideUp();
        }
    });
    
    // Initialisation
    if (!$('#reopen_date').val()) {
        $('#show_countdown').closest('.scm-form-group').hide();
    }
});
