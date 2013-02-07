<?php
/*
Plugin Name: omTaxonomyImages
Plugin URI: http://www.omdesign.cz
Description: Obrazky kategorii
Author: Roman Ožana
Version: 1.0
Author URI: http://www.omdesign.cz
*/

class omTaxonomyImages {


	public static function init() {
		return new self;
	}

	public function __construct() {
		add_action('admin_head', array($this, 'admin_head'));
		add_action('edit_term', array($this, 'save_taxonomy_image'));
		add_action('create_term', array($this, 'save_taxonomy_image'));
	}

	public function admin_head() {
		$taxonomies = get_taxonomies();
		if (is_array($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				if ($taxonomy === 'nav_menu') continue;
				add_action($taxonomy . '_add_form_fields', array($this, 'add_taxonomy_field'));
				add_action($taxonomy . '_edit_form_fields', array($this, 'edit_taxonomy_field'));
			}
		}
	}

	public function add_taxonomy_field() {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
		require __DIR__ . '/templates/add_taxonomy_field.phtml';
		require __DIR__ . '/templates/script.phtml';
	}

	public function edit_taxonomy_field($taxonomy) {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
		require __DIR__ . '/templates/edit_taxonomy_field.phtml';
		require __DIR__ . '/templates/script.phtml';
	}

	public function save_taxonomy_image($term_id) {
		if (isset($_POST['taxonomy_image'])) {
			update_option('taxonomy_image_' . $term_id, (string)$_POST['taxonomy_image']);
		}
	}

	public static function getImageUrl($term_id = null) {
		if (!$term_id) {
			if (is_category())
				$term_id = get_query_var('cat');
			elseif (is_tax()) {
				$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				$term_id = $current_term->term_id;
			}
		}
		return get_option('taxonomy_image_' . $term_id);
	}

}

omTaxonomyImages::init();
