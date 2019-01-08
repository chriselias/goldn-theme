<?php

add_action('admin_menu', 'test_button_menu');

function test_button_menu() {
    add_submenu_page('themes.php', 'Elementor Update', 'Elementor Update', 'manage_options', 'elementor-update', 'elementor_update_admin_page');
}

function elementor_update_admin_page() {

    // This function creates the output for the admin page.
    // It also checks the value of the $_POST variable to see whether
    // there has been a form submission. 

    // The check_admin_referer is a WordPress function that does some security
    // checking and is recommended good practice.

    // General check for user permissions.
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.')    );
    }

    // Start building the page

    echo '<div class="wrap">';

    echo '<h2>Elementor Update</h2>';

    // Check whether the button has been pressed AND also check the nonce
    if (isset($_POST['test_button']) && check_admin_referer('elementor_button_clicked')) {
        // the button has been pressed AND we've passed the security check
        import_elements();
    }

    echo '<form action="themes.php?page=elementor-update" method="post">';

    // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
    wp_nonce_field('elementor_button_clicked');
    echo '<input type="hidden" value="true" name="test_button" />';
    submit_button('Import Elements');
    echo '</form>';

    echo '</div>';
}

function import_elements() {
    switch_to_blog( 1 );

    // Get elementor elements from parent site
    $parent_elements = get_posts(array(
        'posts_per_page' => -1,
        'offset' => 0,
        'post_type' => array(
            'elementor_library'
        )
    ));

    // Get elementor post meta from parent site
    foreach($parent_elements as $parent_element) {
        $parent_element->meta = get_post_custom($parent_element->ID);

        // Transform element post meta
        foreach($parent_element->meta as $key => $value) {
            foreach($value as $metakey => $metavalue) {
                $parent_element->meta[$key] = $metavalue;
            }
        }
    }

    restore_current_blog();

    // Get elementor elements from child site
    $child_elements = get_posts(array(
        'posts_per_page' => -1,
        'offset' => 0,
        'post_type' => array(
            'elementor_library'
        )
    ));

    // Delete elementor elements from child site
    foreach($child_elements as $child_element) {
        wp_delete_post($child_element->ID, true);
    }

    $get_users = get_users( array( 'blog_id' => get_current_blog_id() ) );
    $owner_id = $get_users[0]->ID;

    // Insert elements from parent into child site
    foreach ($parent_elements as $element) {
        wp_insert_post(array(
            'post_content' => $element->post_content,
            'post_name' => sanitize_title($element->post_title),
            'post_title' => $element->post_title,
            'post_type' => $element->post_type,
            'post_status' => 'publish',
            'post_author' => $owner_id,
            'meta_input' => wp_slash($element->meta)
        ));
    }

    echo '<div id="message" class="updated fade"><p>Success! Imported elements.</p></div>';
}  