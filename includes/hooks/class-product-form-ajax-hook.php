<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Product_Form_Ajax_Hook extends Abstract_Hook
{
    private $tags;

    public function __construct(){
        $this->tags = array();
    }

    public function get_categories_attr_ajax( $attr, $selected_cat ) {
        $nodes = $this->build_tree($attr['categories'], $selected_cat);
        //error_log(print_r($nodes, true), E_USER_WARNING);
        echo json_encode( [ 'nodes' => $nodes, 'tags' => $this->tags ] );
        die();
    }

	private function build_tree( $categories, $selected_cat) {
		$cat = [];
    	foreach ($categories as $key => $category){
    		$cat[$key]['id'] = 'id-cat-'.$category['id'];
		    $cat[$key]['title'] = $category['name'];
		    $cat[$key]['value'] = $category['id'];
		    $cat[$key]['level'] = '0'.($category['depth']+1);
		    $cat[$key]['checked'] = $this->get_checked_cat( $category['id'], $selected_cat );
		    $cat[$key]['has_children'] = false;
		    if ( sizeof($category['children']) > 0 ){
			    $cat[$key]['has_children'] = true;
			    $cat[$key]['children'] = $this->build_tree($category['children'], $selected_cat);

		    }
		    if ( $cat[$key]['checked'] ) {
			    $this->tags[] = ['id' => $cat[$key]['id'], 'title' => $cat[$key]['title']];
		    }

	    }
	    return $cat;
	}
/*
    private function build_tree( $cat, $selected_cat, $pid="", $level=1 ) {
        $id = 0;
        $l=$level;
        $nodes = [];
        foreach ( $cat as $key => $value ) {
            $iid = ''.$pid.$id;
            $tree = [
                'id' => 'id-cat-'.$iid,
                'title' => $key,
                'level' => '0'.$level,
                'value' => $key,
                'children' => [],
                'has_children' => false,
                'checked' => $this->get_checked_cat( $key, $selected_cat ),
            ];

            if ( $tree['checked'] ) {
                $this->tags[] = ['id' => $tree['id'], 'title' => $key];
            }

            if ( sizeof($value) > 0 ){
                $l++;
                $tree['has_children'] =true;
                $tree['children'] = $this->build_tree($value, $selected_cat, $iid, $l);
                $l--;

            }

            $id++;
            $nodes[] = $tree;
        }
        return $nodes;

    }
*/

    private function get_checked_cat( $check_key, $selected_cat ) {
    	if(!is_array($selected_cat)){
    		return false;
	    }
        foreach ( $selected_cat as $value ) {
            if ( $check_key == $value ) {
                return true;
            }
        }
        return false;
    }

    public function get_mint_state_ajax( $attr ) {
	        $this->send_response( 'mintState', 'mint-state-select', $attr );
    }

    public function get_grade_service_ajax( $attr ) {
        $this->send_response( 'grade', 'grade-service-select', $attr );
    }

    public function get_grade_service_root_ajax( $attr ) {
        $this->send_response_root( 'grade-service-root', $attr );
    }

    private function send_response_root( $ajax_field, $attr ) {
        if ( isset( $_POST[ $ajax_field ] ) ) {
            $root = $_POST[ $ajax_field ];
            $items = $attr[ $root ];
            if ( $items ) {
                echo json_encode( $items );
            } else {
                echo 'empty';
            }
            die();
        }
    }

    private function send_response($attr_name, $ajax_field, $attr ) {
        if ( isset( $_POST[ $ajax_field ] ) ) {
            $root = $_POST[ $ajax_field ];
            $items = $attr[ $attr_name ][ $root ];
            if ( $items ) {
                echo json_encode( $items );
            } else {
                echo 'empty';
            }
            die();
        }
    }
}