<?php
if (!defined('ABSPATH')) {
    exit;
}

// Utiliser la méthode statique pour obtenir les paramètres avec valeurs par défaut
$settings = Site_Closure_Manager::get_settings();
$wp_roles = wp_roles();
?>

<div class="wrap scm-admin-wrap">
    <h1>
        <span class="dashicons dashicons-lock"></span>
        Site Closure Manager
    </h1>
    
    <p class="description">
        Configurez la fermeture temporaire de votre site avec une page de maintenance personnalisée.
    </p>
    
    <div class="scm-admin-container">
        <div class="scm-main-content">
            <form id="scm-settings-form">
                <?php wp_nonce_field('scm_settings_action', 'scm_settings_nonce'); ?>
                
                <!-- Activation -->
                <div class="scm-section">
                    <h2>État de la fermeture</h2>
                    <div class="scm-toggle-wrapper">
                        <label class="scm-toggle">
                            <input type="checkbox" name="enabled" id="scm-enabled" 
                                   <?php checked($settings['enabled'], true); ?>>
                            <span class="scm-toggle-slider"></span>
                        </label>
                        <label for="scm-enabled" class="scm-toggle-label">
                            <strong>Activer la fermeture du site</strong>
                            <span class="description">Quand activé, le site affichera la page de maintenance selon les dates définies</span>
                        </label>
                    </div>
                    
                    <?php if ($settings['enabled']) : ?>
                        <div class="scm-alert scm-alert-warning">
                            <span class="dashicons dashicons-warning"></span>
                            <strong>Attention !</strong> La fermeture du site est actuellement activée.
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Dates de fermeture -->
                <div class="scm-section">
                    <h2>Planification</h2>
                    
                    <div class="scm-form-grid">
                        <div class="scm-form-group">
                            <label for="closure_date">
                                Date de fermeture <span class="required">*</span>
                            </label>
                            <input type="date" 
                                   name="closure_date" 
                                   id="closure_date" 
                                   class="scm-input"
                                   value="<?php echo esc_attr($settings['closure_date']); ?>"
                                   required>
                        </div>
                        
                        <div class="scm-form-group">
                            <label for="closure_time">
                                Heure de fermeture
                            </label>
                            <input type="time" 
                                   name="closure_time" 
                                   id="closure_time" 
                                   class="scm-input"
                                   value="<?php echo esc_attr($settings['closure_time']); ?>">
                        </div>
                        
                        <div class="scm-form-group">
                            <label for="reopen_date">
                                Date de réouverture
                            </label>
                            <input type="date" 
                                   name="reopen_date" 
                                   id="reopen_date" 
                                   class="scm-input"
                                   value="<?php echo esc_attr($settings['reopen_date']); ?>">
                            <span class="description">Laissez vide pour une fermeture indéfinie</span>
                        </div>
                        
                        <div class="scm-form-group">
                            <label for="reopen_time">
                                Heure de réouverture
                            </label>
                            <input type="time" 
                                   name="reopen_time" 
                                   id="reopen_time" 
                                   class="scm-input"
                                   value="<?php echo esc_attr($settings['reopen_time']); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Contenu de la page -->
                <div class="scm-section">
                    <h2>Contenu de la page</h2>
                    
                    <div class="scm-form-group">
                        <label for="page_title">
                            Titre de la page <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="page_title" 
                               id="page_title" 
                               class="scm-input"
                               value="<?php echo esc_attr($settings['page_title']); ?>"
                               required>
                    </div>
                    
                    <div class="scm-form-group">
                        <label for="page_message">
                            Message <span class="required">*</span>
                        </label>
                        <textarea name="page_message" 
                                  id="page_message" 
                                  class="scm-textarea"
                                  rows="5"
                                  required><?php echo esc_textarea($settings['page_message']); ?></textarea>
                        <span class="description">Le message affiché aux visiteurs</span>
                    </div>
                    
                    <div class="scm-form-group">
                        <label>
                            <input type="checkbox" 
                                   name="show_countdown" 
                                   id="show_countdown"
                                   <?php checked($settings['show_countdown'], true); ?>>
                            Afficher le compte à rebours
                        </label>
                        <span class="description">Affiche un compte à rebours jusqu'à la réouverture (nécessite une date de réouverture)</span>
                    </div>
                </div>
                
                <!-- Design -->
                <div class="scm-section">
                    <h2>Apparence</h2>
                    
                    <div class="scm-form-grid scm-color-grid">
                        <div class="scm-form-group">
                            <label for="background_color">Couleur de fond</label>
                            <input type="text" 
                                   name="background_color" 
                                   id="background_color" 
                                   class="scm-color-picker"
                                   value="<?php echo esc_attr($settings['background_color']); ?>">
                        </div>
                        
                        <div class="scm-form-group">
                            <label for="text_color">Couleur du texte</label>
                            <input type="text" 
                                   name="text_color" 
                                   id="text_color" 
                                   class="scm-color-picker"
                                   value="<?php echo esc_attr($settings['text_color']); ?>">
                        </div>
                        
                        <div class="scm-form-group">
                            <label for="accent_color">Couleur d'accent</label>
                            <input type="text" 
                                   name="accent_color" 
                                   id="accent_color" 
                                   class="scm-color-picker"
                                   value="<?php echo esc_attr($settings['accent_color']); ?>">
                        </div>
                    </div>
                    
                    <div class="scm-form-group">
                        <label for="logo_url">Logo du site</label>
                        <div class="scm-media-wrapper">
                            <input type="url" 
                                   name="logo_url" 
                                   id="logo_url" 
                                   class="scm-input"
                                   value="<?php echo esc_url($settings['logo_url']); ?>"
                                   readonly>
                            <button type="button" class="button" id="upload-logo-btn">
                                Choisir une image
                            </button>
                            <?php if (!empty($settings['logo_url'])) : ?>
                                <button type="button" class="button" id="remove-logo-btn">
                                    Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($settings['logo_url'])) : ?>
                            <div class="scm-logo-preview">
                                <img src="<?php echo esc_url($settings['logo_url']); ?>" alt="Logo preview">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Contact -->
                <div class="scm-section">
                    <h2>Informations de contact</h2>
                    
                    <div class="scm-form-group">
                        <label for="contact_email">Email de contact</label>
                        <input type="email" 
                               name="contact_email" 
                               id="contact_email" 
                               class="scm-input"
                               value="<?php echo esc_attr($settings['contact_email']); ?>">
                        <span class="description">Affiché pour les questions urgentes</span>
                    </div>
                    
                    <div class="scm-form-group">
                        <label>
                            <input type="checkbox" 
                                   name="show_social" 
                                   id="show_social"
                                   <?php checked($settings['show_social'], true); ?>>
                            Afficher les liens réseaux sociaux
                        </label>
                    </div>
                    
                    <div id="social-links-section" style="<?php echo !$settings['show_social'] ? 'display:none;' : ''; ?>">
                        <div class="scm-form-grid">
                            <div class="scm-form-group">
                                <label for="facebook_url">Facebook</label>
                                <input type="url" 
                                       name="facebook_url" 
                                       id="facebook_url" 
                                       class="scm-input"
                                       value="<?php echo esc_url($settings['facebook_url']); ?>"
                                       placeholder="https://facebook.com/votrepage">
                            </div>
                            
                            <div class="scm-form-group">
                                <label for="twitter_url">Twitter/X</label>
                                <input type="url" 
                                       name="twitter_url" 
                                       id="twitter_url" 
                                       class="scm-input"
                                       value="<?php echo esc_url($settings['twitter_url']); ?>"
                                       placeholder="https://twitter.com/votrecompte">
                            </div>
                            
                            <div class="scm-form-group">
                                <label for="instagram_url">Instagram</label>
                                <input type="url" 
                                       name="instagram_url" 
                                       id="instagram_url" 
                                       class="scm-input"
                                       value="<?php echo esc_url($settings['instagram_url']); ?>"
                                       placeholder="https://instagram.com/votrecompte">
                            </div>
                            
                            <div class="scm-form-group">
                                <label for="linkedin_url">LinkedIn</label>
                                <input type="url" 
                                       name="linkedin_url" 
                                       id="linkedin_url" 
                                       class="scm-input"
                                       value="<?php echo esc_url($settings['linkedin_url']); ?>"
                                       placeholder="https://linkedin.com/company/votresociete">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Exceptions -->
                <div class="scm-section">
                    <h2>Accès autorisé</h2>
                    
                    <div class="scm-form-group">
                        <label>Rôles pouvant accéder au site pendant la fermeture :</label>
                        <div class="scm-roles-grid">
                            <?php foreach ($wp_roles->roles as $role_key => $role) : ?>
                                <label class="scm-checkbox-label">
                                    <input type="checkbox" 
                                           name="bypass_roles[]" 
                                           value="<?php echo esc_attr($role_key); ?>"
                                           <?php checked(in_array($role_key, $settings['bypass_roles'])); ?>>
                                    <?php echo esc_html($role['name']); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="scm-actions">
                    <button type="submit" class="button button-primary button-large">
                        <span class="dashicons dashicons-saved"></span>
                        Enregistrer les modifications
                    </button>
                    
                    <a href="<?php echo esc_url(home_url('?scm_preview=1')); ?>" 
                       class="button button-large" 
                       target="_blank">
                        <span class="dashicons dashicons-visibility"></span>
                        Prévisualiser
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Sidebar -->
        <div class="scm-sidebar">
            <div class="scm-widget">
                <h3>💡 Conseils d'utilisation</h3>
                <ul>
                    <li>Définissez toujours une date de fermeture précise</li>
                    <li>Ajoutez une date de réouverture pour activer le compte à rebours</li>
                    <li>Testez votre page avec le bouton "Prévisualiser"</li>
                    <li>Les administrateurs peuvent toujours accéder au site</li>
                </ul>
            </div>
            
            <div class="scm-widget">
                <h3>📋 État actuel</h3>
                <?php
                $is_closed = false;
                if (!empty($settings['enabled']) && !empty($settings['closure_date'])) {
                    $closure_datetime = strtotime($settings['closure_date'] . ' ' . $settings['closure_time']);
                    $current_time = current_time('timestamp');
                    
                    if ($current_time >= $closure_datetime) {
                        if (!empty($settings['reopen_date'])) {
                            $reopen_datetime = strtotime($settings['reopen_date'] . ' ' . $settings['reopen_time']);
                            if ($current_time < $reopen_datetime) {
                                $is_closed = true;
                            }
                        } else {
                            $is_closed = true;
                        }
                    }
                }
                ?>
                
                <?php if ($is_closed) : ?>
                    <div class="scm-status scm-status-closed">
                        <span class="dashicons dashicons-lock"></span>
                        <strong>Site fermé</strong>
                        <p>Le site affiche actuellement la page de maintenance</p>
                    </div>
                <?php else : ?>
                    <div class="scm-status scm-status-open">
                        <span class="dashicons dashicons-unlock"></span>
                        <strong>Site ouvert</strong>
                        <p>Le site est accessible normalement</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="scm-widget">
                <h3>🛟 Support</h3>
                <p>Besoin d'aide ? Contactez le support ou consultez la documentation.</p>
                <a href="#" class="button button-secondary" style="width: 100%;">
                    Documentation
                </a>
            </div>
        </div>
    </div>
</div>

<div id="scm-save-message" class="notice notice-success" style="display: none;">
    <p><strong>Paramètres sauvegardés avec succès !</strong></p>
</div>

<div id="scm-error-message" class="notice notice-error" style="display: none;">
    <p><strong>Erreur :</strong> <span id="error-text"></span></p>
</div>
