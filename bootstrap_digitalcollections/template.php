<?php
/**
 * @file
 * The primary PHP file for this theme.
 */

/* Preprocess page.tpl.php to inject search forms w/o depending on blocks. */
function bootstrap_digitalcollections_preprocess_page(&$variables){
  $search_form = drupal_get_form('search_form');
  $search_box = drupal_render($search_form);
  $variables['search_box'] = $search_box;

  $islandora_search_form = drupal_get_form('islandora_solr_simple_search_form');
  $islandora_search_box = drupal_render($islandora_search_form);
  $variables['islandora_search_box'] = $islandora_search_box;

  /* Only change #navbar's container class to fluid-container */
  $variables['navbar_classes_array'] = array('navbar');

  if (bootstrap_setting('navbar_position') !== '') {
    $variables['navbar_classes_array'][] = 'navbar-' . bootstrap_setting('navbar_position');
  }
  else {
    $variables['navbar_classes_array'][] = 'fluid-container';
  }
  if (bootstrap_setting('navbar_inverse')) {
    $variables['navbar_classes_array'][] = 'navbar-inverse';
  }
  else {
    $variables['navbar_classes_array'][] = 'navbar-default';
  }
}

/* Form alter to add missing bootstrap classes and role to search form. */
function bootstrap_digitalcollections_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_form') {
    $form['#attributes']['role'][] = 'search';
    $form['#attributes']['class'][] = 'hidden-xs';
    $form['basic']['keys']['#attributes']['placeholder'] = t('Site Search'); #found in all/themes/bootstrap/includes/alter.inc
  }

  if ($form_id == 'islandora_solr_simple_search_form') {
    $form['#attributes']['role'][] = 'search';
    $form['#attributes']['class'][] = 'search-form visible-xs';
    $form['simple']['islandora_simple_search_query']['#title'] = t('');
    $form['simple']['islandora_simple_search_query']['#attributes']['placeholder'] = t('Search Digital Collections');
    $form['simple']['submit']['#value'] = t('');
    $form['simple']['submit']['#attributes']['class'][] = 'icon glyphicon glyphicon-search';
  }
}

function bootstrap_digitalcollections_breadcrumb(array $variables) {
  // Use the Path Breadcrumbs theme function if it should be used instead.
  if (_bootstrap_use_path_breadcrumbs()) {
    return path_breadcrumbs_breadcrumb($variables);
  }

  $output = '';
  $breadcrumb = $variables['breadcrumb'];

  // Determine if we are to display the breadcrumb.
  $bootstrap_breadcrumb = bootstrap_setting('breadcrumb');
  if (($bootstrap_breadcrumb == 1 || ($bootstrap_breadcrumb == 2 && arg(0) == 'admin')) && !empty($breadcrumb)) {
    
    /* Changes top-level collection link text. */    
    $unwantedValue = '<a href="/islandora">Islandora Repository</a>';
    $results = array_keys( $breadcrumb, $unwantedValue, true );

    foreach( $results as $match ) {
      $breadcrumb[ $match ] = '<a href="/islandora">Vanderbilt Digital Collections</a>';
    }

    /*
      When linking to an Islandora object in the menu, Drupal assigns the page title and active breadcrumb based 
      on the menu item name somehow instead of the Islandora object's item label. Not an ideal solution, but this
      works to override that strange behavior.
    */
    if ( drupal_get_title() === 'Collections' ) {
      drupal_set_title( 'Library' );

      $breadcrumb_active = array_pop( $breadcrumb );
      $breadcrumb_active[ 'data' ] = drupal_get_title();
      array_push( $breadcrumb, $breadcrumb_active );
    }

    $build = array(
      '#theme' => 'item_list__breadcrumb',
      '#attributes' => array(
        'class' => array('breadcrumb'),
      ),
      '#items' => $breadcrumb,
      '#type' => 'ol',
    );

    $output = drupal_render($build);
  }
  return $output;
}
