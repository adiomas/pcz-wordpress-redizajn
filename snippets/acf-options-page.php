<?php
/**
 * ACF Options Page - Site Settings
 * 
 * Ovaj snippet registrira ACF Options Page "Site Settings"
 * koja služi za globalne postavke stranice uključujući Mega Menu podatke.
 * 
 * @package pcz_Redizajn
 * @since 1.0.0
 * 
 * INSTALACIJA:
 * 1. Dodati ovaj kod u Code Snippets plugin
 * 2. Ili dodati u functions.php child teme
 * 3. Ili koristiti kao mu-plugin
 */

// Sprječava direktan pristup
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registrira ACF Options Page
 */
if ( function_exists( 'acf_add_options_page' ) ) {
    
    acf_add_options_page( array(
        'page_title'    => 'Site Settings',
        'menu_title'    => 'Site Settings',
        'menu_slug'     => 'site-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'icon_url'      => 'dashicons-admin-generic',
        'position'      => 80,
        'update_button' => __( 'Spremi postavke', 'pcz' ),
        'updated_message' => __( 'Postavke su spremljene.', 'pcz' ),
    ) );
    
    // Opcionalno: Dodaj subpage ako treba više kategorija
    /*
    acf_add_options_sub_page( array(
        'page_title'    => 'Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'site-settings',
    ) );
    
    acf_add_options_sub_page( array(
        'page_title'    => 'Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'site-settings',
    ) );
    */
}

