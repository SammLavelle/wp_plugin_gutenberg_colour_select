<?php
   /*
    Plugin Name: Gutenberg Colour Picker
    Description: Adds colour pcikers to customiser to set default gutenberg colours.
    Version: 1
    Author: Samantha Lavelle
    Requires at least: 5
   */


require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/* Customiser modifications */
function customize_additional_settings($wp_customize) {
    /* Add settings for the site colours */
    $wp_customize->add_setting('custom_primary_color', array(
      'default' => '#941C3F',
    ));
    $wp_customize->add_setting('custom_secondary_color', array(
      'default' => '#003e42',
    ));
    $wp_customize->add_setting('custom_tertiary_color', array(
      'default' => '#003e42',
    ));
    $wp_customize->add_setting('custom_text_color', array(
      'default' => '#474747',
    ));
    $wp_customize->add_setting('custom_light_grey', array(
      'default' => '#f3f3f6',
    ));
    $wp_customize->add_setting('custom_dark_grey', array(
      'default' => '#474747',
    ));
  
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_primary_color', array(
        'label' => 'Primary Color',
        'section' => 'title_tagline',
        'settings' => 'custom_primary_color',
  
    )));
  
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_secondary_color', array(
        'label' => 'Secondary Color',
        'section' => 'title_tagline',
        'settings' => 'custom_secondary_color',
    )));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_text_color', array(
      'label' => 'Text Color',
      'section' => 'title_tagline',
      'settings' => 'custom_text_color',
  )));
      $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_light_grey', array(
          'label' => 'Light Grey',
          'section' => 'title_tagline',
          'settings' => 'custom_light_grey',
      )));
      $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_dark_grey', array(
          'label' => 'Dark Grey',
          'section' => 'title_tagline',
          'settings' => 'custom_dark_grey',
      )));
  }
  
  add_action('customize_register', 'customize_additional_settings');
  
  /* Create a custom colour object */
  $colours = (object) [
      "primary" => get_theme_mod('custom_primary_color', '#941C3F'),
      "secondary" => get_theme_mod('custom_secondary_color', '#003e42'),
      "text" => get_theme_mod('custom_text_color', '#474747'),
      "white" => "#ffffff",
      "lgrey" => get_theme_mod('custom_light_grey', '#f3f3f6'),
      "dgrey" => get_theme_mod('custom_dark_grey', '#474747'),
      "black" => "#000000"
      
  ];
  
  add_action('wp_head', function ($args) use ($colours) {my_custom_styles($colours);}, 1);
  
  /* add css file with colours to the head */
  function my_custom_styles($colours)
  {
    echo "
    <style>
      :root{
        --color-primary:" . $colours->primary . ";
        --color-secondary:" . $colours->secondary . ";
        --color-body:" . $colours->text . ";
        --color-white:" . $colours->white . ";
        --color-lgrey:" . $colours->lgrey . ";
        --color-dgrey:" . $colours->dgrey . ";
        --color-black:" . $colours->black . ";
      }
    </style>
   ";
  }
  
  /* Add custom colours to gutenberg editor */
  add_theme_support('editor-color-palette', array(
      array(
          'name' => esc_html__('Primary', 'custom'),
          'slug' => 'primary',
          'color' => $colours->primary,
      ),
      array(
          'name' => esc_html__('Secondary', 'custom'),
          'slug' => 'secondary',
          'color' => $colours->secondary,
      ),
      array(
          'name'  => esc_html__('White', 'custom'),
          'slug'  => 'white',
          'color' => $colours->white,
      ),
      array(
          'name'  => esc_html__('Light Grey', 'custom'),
          'slug'  => 'lgrey',
          'color' => $colours->lgrey,
      ),
      array(
          'name'  => esc_html__('Dark grey', 'custom'),
          'slug'  => 'dgrey',
          'color' => $colours->dgrey,
      ),
      array(
          'name'  => esc_html__('Black', 'custom'),
          'slug'  => 'black',
          'color' => $colours->black,
      ),
  
    )
  );
  
  
  //Get the colors formatted for use with gutenberg editor palette
  function output_the_colors()
  {
  
      // get the colors
      $color_palette = current((array) get_theme_support('editor-color-palette'));
  
      // bail if there aren't any colors found
      if (!$color_palette) {
          return;
      }
  
      // output begins
      ob_start();
  
      // output the names in a string
      echo '[';
      foreach ($color_palette as $color) {
          echo "'" . $color['color'] . "', ";
      }
      echo ']';
  
      return ob_get_clean();
  
  }