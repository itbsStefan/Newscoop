<?php
/**
 * Campsite customized Smarty plugin
 * @package Campsite
 */


/**
 * Campsite user_form block plugin
 *
 * Type:     block
 * Name:     user_form
 * Purpose:  Provides a...
 *
 * @param string
 *     $p_params
 * @param string
 *     $p_smarty
 * @param string
 *     $p_content
 *
 * @return
 *
 */
function smarty_block_user_form($p_params, $p_content, &$p_smarty, &$p_repeat)
{
    require_once $p_smarty->_get_plugin_filepath('shared','escape_special_chars');

    // gets the context variable
    $camp = $p_smarty->get_template_vars('campsite');
    $html = '';

    $template = null;
    if (isset($p_params['template'])) {
        $template = new MetaTemplate($p_params['template']);
        if (!$template->defined()) {
            $template = null;
        }
    }
    $templateId = is_null($template) ? $camp->template->identifier : $template->identifier;
    if (!isset($p_params['submit_button'])) {
        $p_params['submit_button'] = 'Submit';
    }

    if (isset($p_content)) {
        $subsType = $camp->subscription->type == 'T' ? 'trial' : 'paid';
        $html = "<form name=\"edit_user\" action=\"\" method=\"post\">\n"
        ."<input type=\"hidden\" name=\"f_tpl\" value=\"$templateId\" />\n"
        ."<input type=\"hidden\" name=\"f_substype\" value=\"".$subsType."\" />\n";
        $html.= $p_content;
        $html.= "<input type=\"submit\" name=\"f_edit_user\" value=\""
        .smarty_function_escape_special_chars($p_params['submit_button'])
        ."\" />\n</form>\n";
    }

    return $html;
} // fn smarty_block_user_form

?>