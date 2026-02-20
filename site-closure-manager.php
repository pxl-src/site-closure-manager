<?php
/**
 * Plugin Name: Site Closure Manager
 * Plugin URI: https://example.com/site-closure-manager
 * Description: Plugin pour fermer temporairement le site WooCommerce à une date précise avec une page de maintenance personnalisée
 * Version: 1.0.4
 * Author: Ludovic Stolycia
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: site-closure-manager
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Sécurité - Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définir les constantes du plugin
define('SCM_VERSION', '1.0.0');
define('SCM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SCM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SCM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Classe principale du plugin
 */
class Site_Closure_Manager {
    
    /**
     * Instance unique du plugin
     */
    private static $instance = null;
    
    /**
     * Obtenir l'instance unique
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructeur
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialiser les hooks WordPress
     */
    private function init_hooks() {
        // Hook d'activation/désactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Menu d'administration
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // Vérifier la fermeture du site
        add_action('template_redirect', array($this, 'check_site_closure'), 1);
        
        // AJAX pour sauvegarder les paramètres (admin)
        add_action('wp_ajax_scm_save_settings', array($this, 'ajax_save_settings'));
        
        // AJAX pour le formulaire de contact (public, connecté ou non)
        add_action('wp_ajax_scm_send_contact',        array($this, 'ajax_send_contact'));
        add_action('wp_ajax_nopriv_scm_send_contact', array($this, 'ajax_send_contact'));
    }
    
    /**
     * Tableau des options par défaut
     */
    private static function default_options() {
        return array(
            'enabled'              => false,
            'closure_date'         => '',
            'closure_time'         => '00:00',
            'reopen_date'          => '',
            'reopen_time'          => '00:00',
            'page_title'           => 'Site temporairement fermé',
            'page_message'         => 'Notre site est actuellement fermé pour maintenance. Nous serons de retour bientôt.',
            'show_countdown'       => true,
            'background_color'     => '#1a1a2e',
            'text_color'           => '#ffffff',
            'accent_color'         => '#0f3460',
            'logo_url'             => '',
            'contact_email'        => get_option('admin_email', ''),
            'contact_mode'         => 'email',
            'form_subject'         => 'Message depuis la page de maintenance',
            'form_success_message' => 'Merci ! Votre message a bien été envoyé.',
            'bypass_roles'         => array('administrator'),
            'show_social'          => false,
            'facebook_url'         => '',
            'twitter_url'          => '',
            'instagram_url'        => '',
            'linkedin_url'         => '',
        );
    }

    /**
     * Activation du plugin
     */
    public function activate() {
        if (!get_option('scm_settings')) {
            add_option('scm_settings', self::default_options());
        }
    }
    
    /**
     * Obtenir les paramètres avec valeurs par défaut
     */
    public static function get_settings() {
        $settings = get_option('scm_settings', array());
        return wp_parse_args($settings, self::default_options());
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        // Ne pas supprimer les options pour les conserver
    }
    
    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            'Site Closure Manager',
            'Fermeture Site',
            'manage_options',
            'site-closure-manager',
            array($this, 'render_admin_page'),
            'dashicons-lock',
            100
        );
    }
    
    /**
     * Enregistrer les paramètres
     */
    public function register_settings() {
        register_setting('scm_settings_group', 'scm_settings');
    }
    
    /**
     * Charger les scripts d'administration
     */
    public function enqueue_admin_scripts($hook) {
        if ('toplevel_page_site-closure-manager' !== $hook) {
            return;
        }
        
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_media();
        
        wp_enqueue_style(
            'scm-admin-style',
            SCM_PLUGIN_URL . 'assets/css/admin-style.css',
            array(),
            SCM_VERSION
        );
        
        wp_enqueue_script(
            'scm-admin-script',
            SCM_PLUGIN_URL . 'assets/js/admin-script.js',
            array('jquery', 'wp-color-picker'),
            SCM_VERSION,
            true
        );
        
        wp_localize_script('scm-admin-script', 'scmAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('scm_nonce')
        ));
    }
    
    /**
     * Vérifier si le site doit être fermé
     */
    public function check_site_closure() {
        // Ne pas bloquer les requêtes AJAX
        if (wp_doing_ajax()) {
            return;
        }
        
        // Ne pas bloquer wp-login.php
        if ($GLOBALS['pagenow'] === 'wp-login.php') {
            return;
        }
        
        $settings = self::get_settings();
        
        // Gestion de la prévisualisation
        if (isset($_GET['scm_preview']) && $_GET['scm_preview'] == '1') {
            // Vérifier que l'utilisateur a les droits
            if (current_user_can('manage_options')) {
                // Vérifier le nonce pour la sécurité (optionnel mais recommandé)
                if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'scm_preview')) {
                    $this->display_closure_page($settings);
                    exit;
                } else {
                    // Afficher quand même pour la compatibilité avec l'ancien lien
                    $this->display_closure_page($settings);
                    exit;
                }
            } else {
                wp_die('Vous n\'avez pas les permissions nécessaires pour prévisualiser cette page.');
            }
        }
        
        // Si la fermeture n'est pas activée
        if (empty($settings['enabled'])) {
            return;
        }
        
        // Vérifier si l'utilisateur peut contourner
        if ($this->can_bypass_closure()) {
            return;
        }
        
        // Vérifier si nous sommes dans la période de fermeture
        if ($this->is_site_closed($settings)) {
            $this->display_closure_page($settings);
            exit;
        }
    }
    
    /**
     * Vérifier si l'utilisateur peut contourner la fermeture
     */
    private function can_bypass_closure() {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $settings = self::get_settings();
        $bypass_roles = isset($settings['bypass_roles']) ? $settings['bypass_roles'] : array('administrator');
        
        $current_user = wp_get_current_user();
        
        foreach ($bypass_roles as $role) {
            if (in_array($role, $current_user->roles)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifier si le site est fermé
     */
    private function is_site_closed($settings) {
        if (empty($settings['closure_date'])) {
            return false;
        }
        
        $closure_datetime = strtotime($settings['closure_date'] . ' ' . $settings['closure_time']);
        $current_time = current_time('timestamp');
        
        // Si la date de fermeture n'est pas encore atteinte
        if ($current_time < $closure_datetime) {
            return false;
        }
        
        // Si une date de réouverture est définie
        if (!empty($settings['reopen_date'])) {
            $reopen_datetime = strtotime($settings['reopen_date'] . ' ' . $settings['reopen_time']);
            
            // Si nous avons dépassé la date de réouverture
            if ($current_time >= $reopen_datetime) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Afficher la page de fermeture
     */
    private function display_closure_page($settings) {
        // Préparer toutes les variables avec des valeurs par défaut
        $title = !empty($settings['page_title']) ? $settings['page_title'] : 'Site temporairement fermé';
        $message = !empty($settings['page_message']) ? $settings['page_message'] : 'Notre site est actuellement fermé.';
        $bg_color = !empty($settings['background_color']) ? $settings['background_color'] : '#1a1a2e';
        $text_color = !empty($settings['text_color']) ? $settings['text_color'] : '#ffffff';
        $accent_color = !empty($settings['accent_color']) ? $settings['accent_color'] : '#0f3460';
        $logo_url = !empty($settings['logo_url']) ? $settings['logo_url'] : '';
        $show_countdown = !empty($settings['show_countdown']) ? $settings['show_countdown'] : false;
        $contact_email = !empty($settings['contact_email']) ? $settings['contact_email'] : '';
        
        // Calculer le temps de réouverture
        $reopen_timestamp = null;
        if (!empty($settings['reopen_date'])) {
            $reopen_time = !empty($settings['reopen_time']) ? $settings['reopen_time'] : '00:00';
            $reopen_timestamp = strtotime($settings['reopen_date'] . ' ' . $reopen_time);
        }
        
        // Définir le header HTTP (sauf en mode prévisualisation)
        if (!isset($_GET['scm_preview'])) {
            status_header(503);
            header('Retry-After: 3600');
        }
        
        include SCM_PLUGIN_DIR . 'templates/closure-page.php';
    }
    
    /**
     * Afficher la page d'administration
     */
    public function render_admin_page() {
        include SCM_PLUGIN_DIR . 'templates/admin-page.php';
    }
    
    /**
     * Sauvegarder les paramètres via AJAX
     */
    public function ajax_save_settings() {
        check_ajax_referer('scm_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission refusée');
        }
        
        $settings = array(
            'enabled'              => isset($_POST['enabled']) && $_POST['enabled'] === 'true',
            'closure_date'         => sanitize_text_field($_POST['closure_date']),
            'closure_time'         => sanitize_text_field($_POST['closure_time']),
            'reopen_date'          => sanitize_text_field($_POST['reopen_date']),
            'reopen_time'          => sanitize_text_field($_POST['reopen_time']),
            'page_title'           => sanitize_text_field($_POST['page_title']),
            'page_message'         => wp_kses_post($_POST['page_message']),
            'show_countdown'       => isset($_POST['show_countdown']) && $_POST['show_countdown'] === 'true',
            'background_color'     => sanitize_hex_color($_POST['background_color']),
            'text_color'           => sanitize_hex_color($_POST['text_color']),
            'accent_color'         => sanitize_hex_color($_POST['accent_color']),
            'logo_url'             => esc_url_raw($_POST['logo_url']),
            'contact_email'        => sanitize_email($_POST['contact_email']),
            'contact_mode'         => sanitize_text_field($_POST['contact_mode']),
            'form_subject'         => sanitize_text_field($_POST['form_subject']),
            'form_success_message' => sanitize_text_field($_POST['form_success_message']),
            'bypass_roles'         => isset($_POST['bypass_roles']) ? array_map('sanitize_text_field', $_POST['bypass_roles']) : array(),
            'show_social'          => isset($_POST['show_social']) && $_POST['show_social'] === 'true',
            'facebook_url'         => esc_url_raw($_POST['facebook_url']),
            'twitter_url'          => esc_url_raw($_POST['twitter_url']),
            'instagram_url'        => esc_url_raw($_POST['instagram_url']),
            'linkedin_url'         => esc_url_raw($_POST['linkedin_url']),
        );
        
        update_option('scm_settings', $settings);
        wp_send_json_success('Paramètres sauvegardés avec succès');
    }

    /**
     * Handler AJAX pour le formulaire de contact public
     */
    public function ajax_send_contact() {
        // Vérifier le nonce
        if (!isset($_POST['scm_contact_nonce']) || !wp_verify_nonce($_POST['scm_contact_nonce'], 'scm_contact_form')) {
            wp_send_json_error('Requête invalide.');
        }

        $settings     = self::get_settings();
        $to           = sanitize_email($settings['contact_email']);
        $from_name    = sanitize_text_field($_POST['scm_name']  ?? '');
        $from_email   = sanitize_email($_POST['scm_email']      ?? '');
        $from_message = sanitize_textarea_field($_POST['scm_message'] ?? '');
        $subject      = !empty($settings['form_subject']) ? $settings['form_subject'] : 'Message depuis la page de maintenance';

        // Validations basiques
        if (empty($from_name) || empty($from_email) || empty($from_message)) {
            wp_send_json_error('Veuillez remplir tous les champs.');
        }
        if (!is_email($from_email)) {
            wp_send_json_error('Adresse e-mail invalide.');
        }
        if (empty($to)) {
            wp_send_json_error('Adresse de destination non configurée.');
        }

        $body = sprintf(
            "Vous avez reçu un message depuis la page de maintenance.\n\nNom : %s\nE-mail : %s\n\nMessage :\n%s",
            $from_name,
            $from_email,
            $from_message
        );

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            sprintf('Reply-To: %s <%s>', $from_name, $from_email),
        );

        $sent = wp_mail($to, $subject, $body, $headers);

        if ($sent) {
            $success_msg = !empty($settings['form_success_message'])
                ? $settings['form_success_message']
                : 'Merci ! Votre message a bien été envoyé.';
            wp_send_json_success($success_msg);
        } else {
            wp_send_json_error('Une erreur est survenue lors de l\'envoi. Veuillez réessayer.');
        }
    }
}
// Initialiser le plugin
function scm_init() {
    return Site_Closure_Manager::get_instance();
}


// Démarrer le plugin
add_action('plugins_loaded', 'scm_init');
