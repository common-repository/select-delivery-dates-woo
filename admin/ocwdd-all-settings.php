<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCWDD_admin_settings')) {

  class OCWDD_admin_settings {

    protected static $OCWDD_instance;

    function OCWDD_submenu_page() {
        add_submenu_page( 'woocommerce', __( 'Delivery Date', 'select-delivery-dates-woo' ), __( 'Delivery Date', 'select-delivery-dates-woo' ), 'manage_options', 'oc-delivery-date',array($this, 'OCWDD_callback'));
    }

    function OCWDD_callback() {
    ?>    
        <div class="ocwdd-container">
            <div class="wrap">
                <h2><?php echo __( 'WooCommerce Delivery Date', 'select-delivery-dates-woo' );?></h2>
                <?php if(isset($_REQUEST['message']) && $_REQUEST['message'] == 'success'){ ?>
                    <div class="notice notice-success is-dismissible"> 
                        <p><strong><?php echo __('Record updated successfully.','select-delivery-dates-woo');?></strong></p>
                    </div>
                <?php } ?>
                <div class="ocwdd-inner-block">
                    <form method="post" >
                        <?php wp_nonce_field( 'ocwdd_nonce_action', 'ocwdd_nonce_field' ); ?>
                        <ul class="tabs">
                            <li class="tab-link current" data-tab="ocwdd-tab-general"><?php echo __( 'General Settings', 'select-delivery-dates-woo' );?></li>
                            <li class="tab-link" data-tab="ocwdd-tab-delivery"><?php echo __( 'Delivery Options', 'select-delivery-dates-woo' );?></li>
                        </ul>
                        <div id="ocwdd-tab-general" class="tab-content current">
                            <fieldset>
                                <div class="ocwdd-top">
                                    <p class="ocwdd-heading"><?php echo __( 'All Basic Settings', 'select-delivery-dates-woo' );?></p>
                                </div>
                                <div class="ocwdd_table_main">
                                    <table class="form-table">
                                        <tbody>
                                            <tr>
                                                <th><label><?php echo __( 'Enable/Disable This Plugin', 'select-delivery-dates-woo' ); ?></label></th>
                                                    <?php
                                                        $ocwdd_enabled = empty(get_option( 'ocwdd_enabled' )) ? 'no' : get_option( 'ocwdd_enabled' );
                                                    ?>
                                                <td>
                                                    <input type="checkbox" name="ocwdd-enabled" value="yes" <?php if ($ocwdd_enabled == "yes") {echo 'checked="checked"';} ?>>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Date format', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php $ocwdd_dateformat = empty(get_option( 'ocwdd_dateformat' )) ? 'Y/m/d' : get_option( 'ocwdd_dateformat' ); ?>
                                                    <select name="ocwdd-dateformat" id="ocwdd_dateformat" class="regular-text">
                                                        <option value="Y/m/d" <?php if($ocwdd_dateformat == 'Y/m/d'){echo "selected";}?>><?php echo date('Y/m/d');?></option>
                                                        <option value="d/m/Y" <?php if($ocwdd_dateformat == 'd/m/Y'){echo "selected";}?>><?php echo date('d/m/Y');?></option>
                                                        <option value="m/d/y" <?php if($ocwdd_dateformat == 'm/d/y'){echo "selected";}?>><?php echo date('m/d/y');?></option>
                                                        <option value="Y-m-d" disabled><?php echo date('Y-m-d');?></option>
                                                        <option value="d-m-Y" disabled><?php echo date('d-m-Y');?></option>
                                                        <option value="m-d-y" disabled><?php echo date('m-d-y');?></option>
                                                        <option value="Y.m.d" disabled><?php echo date('Y.m.d');?></option>
                                                        <option value="d.m.Y" disabled><?php echo date('d.m.Y');?></option>
                                                        <option value="m.d.y" disabled><?php echo date('m.d.y');?></option>
                                                    </select>
                                                    <label class="ocwdd_pro_link"><?php echo __( 'Only available in pro version ', 'select-delivery-dates-woo' );?><a href="https://oceanwebguru.com/shop/select-delivery-dates-woo-pro/" target="_blank"><?php echo __( 'link', 'select-delivery-dates-woo' );?></a></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Delivery Fee Enable', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php
                                                        $ocwdd_delivery_fee = empty(get_option( 'ocwdd_delivery_fee' )) ? 'no' : get_option( 'ocwdd_delivery_fee' );
                                                    ?>
                                                    <input type="checkbox" name="ocwdd_delivery_fee" value="yes" <?php if ($ocwdd_delivery_fee == "yes") {echo 'checked="checked"';} ?>>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Delivery Fee Amount', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php $ocwdd_delivery_fee_amount= get_option('ocwdd_delivery_fee_amount');  ?>
                                                    
                                                    <input type="number" name="ocwdd_delivery_fee_amount" value="<?php echo $ocwdd_delivery_fee_amount; ?>" class="regular-text">
                                                    <p class="ocwdd-tips"><?php echo __( "Note : This is delivery fee", 'select-delivery-dates-woo' );?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo __( 'Choose Delivery Shipping Method', 'select-delivery-dates-woo' );?></th>
                                                <td>
                                                    <?php $ocwdd_shipping_methods = get_option('ocwdd_shipping_method');
                                                    ?>
                                                    <select name="ocwdd_shipping_method[]" multiple class="regular-text">
                                                        <?php
                                                        $str_flag="";
                                                        $ocwdd_shipping_method= get_option('ocwdd_shipping_method'); 
                                                        $shipping_methods = WC()->shipping->get_shipping_methods();
                                                        if(!empty($shipping_methods)){
                                                            foreach($shipping_methods as $shipping_method){ 
                                                                if(!empty($ocwdd_shipping_methods)){
                                                                    if(in_array($shipping_method->id,$ocwdd_shipping_methods)) $str_flag = "selected";
                                                                    else $str_flag="";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $shipping_method->id; ?>" <?php echo $str_flag; ?>><?php echo $shipping_method->method_title; ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo __('Delivery Charges Label' , 'select-delivery-dates-woo'); ?></th>
                                                <td>
                                                    <?php $ocwdd_fee_option_name= get_option('ocwdd_fee_option_name'); ?>
                                                    <input type="text" name="ocwdd_fee_option_name" value="<?php echo $ocwdd_fee_option_name ?>" class="regular-text">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo __('Display For Selected User Roles' , 'select-delivery-dates-woo'); ?></th>
                                                <td>
                                                    <input type="checkbox" class="ocwdd_display_for_u_role" name="ocwdd_display_for_u_role" value="yes" disabled>
                                                    <span><?php echo __('Enable This Checkbox For Select User Roles' , 'select-delivery-dates-woo'); ?></span>
                                                    <label class="ocwdd_pro_link"><?php echo __( 'Only available in pro version ', 'select-delivery-dates-woo' );?><a href="https://oceanwebguru.com/shop/select-delivery-dates-woo-pro/" target="_blank"><?php echo __( 'link', 'select-delivery-dates-woo' );?></a></label>
                                                </td>
                                            </tr>                                          
                                            <tr class="ocwdd_display_select_user">
                                                <th>
                                                    <label><?php echo __("Select User Roles.","select-delivery-dates-woo");?></label>
                                                </th>
                                                <td>
                                                    <select id="ocwdd_select_user_role" name="ocwdd_roles_select[]" multiple="multiple" style="width:60%;" disabled>
                                                        <option value="administrator" selected="selected">administrator</option>
                                                        <option value="editor" selected="selected">editor</option>
                                                        <option value="author" selected="selected">author</option>
                                                        <option value="shop_manager" selected="selected">shop_manager</option>
                                                        <option value="subscriber" selected="selected">subscriber</option>
                                                        <option value="contributor" selected="selected">contributor</option>
                                                        <option value="customer" selected="selected">customer</option>
                                                        <option value="translator" selected="selected">translator</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo __('Enable/Disable Select Off Days' , 'select-delivery-dates-woo'); ?></th>
                                                <td>
                                                    <input type="checkbox" class="ocwdd_ed_select_offday" name="ocwdd_ed_select_offday" value="yes" disabled>
                                                    <span><?php echo __('Enable This Checkbox For Select Off Days' , 'select-delivery-dates-woo'); ?></span>
                                                    <label class="ocwdd_pro_link"><?php echo __( 'Only available in pro version ', 'select-delivery-dates-woo' );?><a href="https://oceanwebguru.com/shop/select-delivery-dates-woo-pro/" target="_blank"><?php echo __( 'link', 'select-delivery-dates-woo' );?></a></label>
                                                </td>
                                            </tr>    
                                            <tr class="ocwdd_display_select_offday">
                                                <th>
                                                    <label><?php echo __("Select Off Days.","select-delivery-dates-woo");?></label>
                                                </th>
                                                <td>
                                                    <div class="ocwdd_select_multiple">
                                                        <a class="add_date_button button-secondary">Add Field</a>
                                                        <div>
                                                            <input type="text" name="ocwdd_multiple_date[]" value="2022/03/23" class="regular-text ocwdd_select_date" readonly>
                                                            <a href="#" class="remove_date">
                                                                <img src="<?php echo OCWDD_PLUGIN_DIR. '/assets/images/remove.png' ;?>">
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <input type="text" name="ocwdd_multiple_date[]" value="2022/03/26" class="regular-text ocwdd_select_date" readonly>
                                                            <a href="#" class="remove_date">
                                                                <img src="<?php echo OCWDD_PLUGIN_DIR. '/assets/images/remove.png' ;?>">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div id="ocwdd-tab-delivery" class="tab-content">
                            <fieldset>
                                <div class="ocwdd-top">
                                    <p class="ocwdd-heading"><?php echo __( 'All Delivery Date Setting', 'select-delivery-dates-woo' );?></p>
                                </div>
                                <div class="ocwdd_table_main">
                                    <table class="form-table">
                                        <tbody>
                                            <tr>
                                                <?php 
                                                 $ocwdd_deliverydate_enableordisable = empty(get_option( 'ocwdd_deliverydate_enableordisable' )) ? false : get_option( 'ocwdd_deliverydate_enableordisable' ); ?>
                                                <th scope="row">
                                                    <label><?php echo __('Delivery Date Enable or Disable'); ?></label>
                                                </th>
                                                <td>
                                                     <input type="checkbox" name="ocwdd_deliverydate_enableordisable" value="true" <?php if ($ocwdd_deliverydate_enableordisable == true) {echo 'checked="checked"';} ?>>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Delivery Date Field (Checkout page)', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php
                                                        $ocwdd_deliverydate_required = empty(get_option( 'ocwdd_deliverydate_required' )) ? false : get_option( 'ocwdd_deliverydate_required' ); ?>
                                                    <p>
                                                        <label>
                                                            <input type="checkbox" name="ocwdd-deliverydate-required" value="true" <?php if ($ocwdd_deliverydate_required == true) {echo 'checked="checked"';} ?>><?php echo __( 'Required / Not Required Delivery Date Field On Checkout Page', 'select-delivery-dates-woo' ); ?>
                                                        </label>
                                                    </p>
                                                    <div class="ocwdd-space"></div>
                                                    <p class="ocwdd-tips"><?php echo __( "Note: By Default This Field is Not Required", 'select-delivery-dates-woo' );?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __('Delivery Date Field Position'); ?></label>
                                                </th>
                                                <td>
                                                    <?php $datefieldposition= get_option('datefieldposition'); ?>
                                                    <select name="datefieldposition" id="datefieldposition" class="regular-text">
                                                        <option value="before_notes" <?php if($datefieldposition == 'before_notes'){  echo "selected"; } ?>><?php echo __( 'Before Notes', 'select-delivery-dates-woo' );?></option>
                                                        <option value="after_notes" <?php if($datefieldposition == 'after_notes'){ echo "selected"; } ?>><?php echo __( 'After Notes', 'select-delivery-dates-woo' );?></option>
                                                        <option value="before_billing" <?php if($datefieldposition == 'before_billing'){ echo "selected"; } ?>><?php echo __( 'Before Billing', 'select-delivery-dates-woo' );?></option>
                                                        <option value="after_billing" <?php if($datefieldposition == 'after_billing'){ echo "selected"; } ?>><?php echo __( 'After Billing', 'select-delivery-dates-woo' );?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Custom Message', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php $ocwdd_custommessage = empty(get_option( 'ocwdd_custommessage' )) ? 'Delivery Date' : get_option( 'ocwdd_custommessage' ); ?>
                                                    <input type="text" name="ocwdd-custommessage" value="<?php echo $ocwdd_custommessage; ?>" class="regular-text">
                                                    <p class="ocwdd-tips"><?php echo __( "Note: This will be shown besides the estimated date", 'select-delivery-dates-woo' );?></p>
                                                </td>                                         
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Starting Delivery Date', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <input type="text" name="ocwdd-starting_date" value="<?php echo get_option( 'ocwdd_starting_date' ); ?>" id="ocwdd_starting_date" autocomplete="off" class="regular-text" readonly>
                                                    <p class="ocwdd-tips"><?php echo __( "Note: Set Starting Delivery Date", 'select-delivery-dates-woo' );?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Ending Delivery Date', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <input type="text" name="ocwdd-ending_date" value="<?php echo get_option( 'ocwdd_ending_date' ); ?>" id="ocwdd_ending_date" autocomplete="off" class="regular-text" readonly>
                                                    <p class="ocwdd-tips"><?php echo __( "Note: Set Ending Delivery Date", 'select-delivery-dates-woo' );?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Delivery Workday', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <?php 
                                                    $ocwdd_workday = get_option( 'ocwdd_workday', array(0, 1, 2, 3, 4, 5, 6) );
                                                ?>
                                                <td>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="0" <?php if(in_array('0', $ocwdd_workday)){ echo "checked";}?>><span>Sunday</span></div>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="1" <?php if(in_array('1', $ocwdd_workday)){ echo "checked";}?>><span>Monday</span></div>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="2" <?php if(in_array('2', $ocwdd_workday)){ echo "checked";}?>><span>Tuesday</span></div>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="3" <?php if(in_array('3', $ocwdd_workday)){ echo "checked";}?>><span>Wednesday</span></div>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="4" <?php if(in_array('4', $ocwdd_workday)){ echo "checked";}?>><span>Thursday</span></div>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="5" <?php if(in_array('5', $ocwdd_workday)){ echo "checked";}?>><span>Friday</span></div>
                                                    <div class="ocwdd-workday"><input type="checkbox" name="ocwdd-workday[]" value="6" <?php if(in_array('6', $ocwdd_workday)){ echo "checked";}?>><span>Saturday</span></div>
                                                    <p class="ocwdd-tips"><?php echo __( "Choose delivery workday. Note: Default all day workday.", 'select-delivery-dates-woo' );?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="ocwdd-top">
                                    <p class="ocwdd-heading"><?php echo __( 'All Delivery Time Setting', 'select-delivery-dates-woo' );?></p>
                                </div>
                                <div class="ocwdd_table_main">
                                    <table class="form-table">
                                        <tbody>
                                            <tr>
                                                <?php 
                                                 $ocwdd_deliverytime_enableordisable = empty(get_option( 'ocwdd_deliverytime_enableordisable' )) ? false : get_option( 'ocwdd_deliverytime_enableordisable' ); ?>
                                                <th scope="row">
                                                    <label><?php echo __('Delivery Time Enable or Disable'); ?></label>
                                                </th>
                                                <td>
                                                     <input type="checkbox" name="ocwdd_deliverytime_enableordisable" value="true" <?php if ($ocwdd_deliverytime_enableordisable == true) {echo 'checked="checked"';} ?>>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Delivery Time Field (Checkout page)', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php
                                                        $ocwdd_deliverytime_required = empty(get_option( 'ocwdd_deliverytime_required' )) ? false : get_option( 'ocwdd_deliverydate_required' );
                                                    ?>
                                                    <p>
                                                        <label>
                                                            <input type="checkbox" name="ocwdd-deliverytime-required" value="true" <?php if ($ocwdd_deliverytime_required == true) {echo 'checked="checked"';} ?>><?php echo __( 'Required / Not Required Delivery Time Field On Checkout Page', 'select-delivery-dates-woo' ); ?>
                                                        </label>
                                                    </p>
                                                   
                                                    <div class="ocwdd-space"></div>
                                                    <p class="ocwdd-tips"><?php echo __( "Note: By Default This Field is Not Required", 'select-delivery-dates-woo' );?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __('Delivery Time Field Position'); ?></label>
                                                </th>
                                                <td>
                                                    <?php $timefieldposition= get_option('timefieldposition'); ?>
                                                    <select name="timefieldposition" id="timefieldposition" class="regular-text">
                                                        <option value="before_notes" <?php if($timefieldposition == 'before_notes'){  echo "selected"; }?>><?php echo __( 'Before Notes', 'select-delivery-dates-woo' );?></option>
                                                        <option value="after_notes" <?php if($timefieldposition == 'after_notes'){ echo "selected"; }?>><?php echo __( 'After Notes', 'select-delivery-dates-woo' );?></option>
                                                        <option value="before_billing" <?php if($timefieldposition == 'before_billing'){ echo "selected"; }?>><?php echo __( 'Before Billing', 'select-delivery-dates-woo' );?></option>
                                                        <option value="after_billing" <?php if($timefieldposition == 'after_billing'){ echo "selected"; }?>><?php echo __( 'After Billing', 'select-delivery-dates-woo' );?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <label><?php echo __( 'Delivery Time field text', 'select-delivery-dates-woo' );?></label>
                                                </th>
                                                <td>
                                                    <?php $ocwdd_deliverytime = empty(get_option( 'ocwdd_deliverytime' )) ? 'Delivery Time' : get_option( 'ocwdd_deliverytime' ); ?>
                                                    <input type="text" name="ocwdd-delivery_time" value="<?php echo $ocwdd_deliverytime; ?>" class="regular-text">
                                                    <p class="ocwdd-tips"><?php echo __( "Note: This will be shown besides the estimated time", 'select-delivery-dates-woo' );?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo __( 'Delivery time suffix Add', 'select-delivery-dates-woo' );?></th>
                                                <td>
                                                    <input type="text" name="ocwdd_time_suffix" value="<?php echo stripslashes(get_option( 'ocwdd_time_suffix' )); ?>" id="ocwdd_time_suffix" placeholder="o'clock" autocomplete="off" class="regular-text">
                                                    <p class="ocwdd-tips"><?php echo __( "Note:You can add ex. o'clock suffix and any other suffix you can add here . ", 'select-delivery-dates-woo' );?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo __( 'Delivery Time Option', 'select-delivery-dates-woo' );?></th>
                                                <td>
                                                      <p class="ocwdd-tips"><?php echo __( "Add Here All Time That your Delivery Availabel ex. 2.30am to 3.30pm , 2.30am , 9.00 ", 'select-delivery-dates-woo' );?></p>
                                                    <div class="ocwdd_input_fields_wrap">
                                                        <a class="add_field_button button-secondary">Add Field</a>
                                                        <?php $delivery_time_option  = get_option('delivery_time_option');

                                                        if(!empty($delivery_time_option)){

                                                            foreach ($delivery_time_option as $keyy => $valuee) { ?>
                                                                <div>
                                                                    <input type="text" name="delivery_time_option[]" value="<?php echo $valuee; ?>" class="regular-text">
                                                                    <a href="#" class="remove_field">
                                                                        <img src="<?php echo OCWDD_PLUGIN_DIR. '/assets/images/remove.png' ;?>">
                                                                    </a>
                                                                </div>
                                                                
                                                            <?php } 

                                                        } else { ?> 

                                                            <div><input type="text" name="delivery_time_option[]" class="regular-text"></div>
                                                            
                                                        <?php } ?>

                                                    </div>                                           
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <input type="hidden" name="ocwdd_action" value="ocwdd_save_option_data"/>
                        <input type="submit" value="Save changes" name="submit" class="button-primary" id="ocwdd-btn-space">
                    </form> 
                </div>
            </div>
        </div>
    <?php
    }

    function recursive_sanitize_text_field($array) {

        $new_arr = array();
        foreach ( $array as $key => $value ) {
            if ( is_array( $value ) ) {
                $value = recursive_sanitize_text_field($value);
            }else{
                $value = sanitize_text_field( $value );
                $new_arr[] = $value;
            }
        }
        return $new_arr;
    }

    // Save Setting Option
    function OCWDD_save_options() {
        if( current_user_can('administrator') ) { 
            if(isset($_REQUEST['ocwdd_action']) && $_REQUEST['ocwdd_action'] == 'ocwdd_save_option_data'){
                if(!isset( $_POST['ocwdd_nonce_field'] ) || !wp_verify_nonce( $_POST['ocwdd_nonce_field'], 'ocwdd_nonce_action' ) ){
                    print 'Sorry, your nonce did not verify.';
                    exit;

                }else{

                    $ocwdd_enabled = (!empty(sanitize_text_field( $_REQUEST['ocwdd-enabled'] )))? sanitize_text_field( $_REQUEST['ocwdd-enabled'] ) : 'no';
                    $ocwdd_delivery_fee = (!empty(sanitize_text_field( $_REQUEST['ocwdd_delivery_fee'] )))? sanitize_text_field( $_REQUEST['ocwdd_delivery_fee'] ) : 'no';
                    $ocwdd_deliverydate_required = (!empty(sanitize_text_field( $_REQUEST['ocwdd-deliverydate-required'] )))? sanitize_text_field( $_REQUEST['ocwdd-deliverydate-required'] ) : false;
                    $ocwdd_deliverydate_enableordisable = (!empty(sanitize_text_field( $_REQUEST['ocwdd_deliverydate_enableordisable'] )))? sanitize_text_field( $_REQUEST['ocwdd_deliverydate_enableordisable'] ) : false;
                    $ocwdd_deliverytime_enableordisable = (!empty(sanitize_text_field( $_REQUEST['ocwdd_deliverytime_enableordisable'] )))? sanitize_text_field( $_REQUEST['ocwdd_deliverytime_enableordisable'] ) : false;
                    $ocwdd_deliverytime_required = (!empty(sanitize_text_field( $_REQUEST['ocwdd-deliverytime-required'] )))? sanitize_text_field( $_REQUEST['ocwdd-deliverytime-required'] ) : false;
                    $ocwdd_custommessage = (!empty(sanitize_text_field( $_REQUEST['ocwdd-custommessage'] )))? sanitize_text_field( $_REQUEST['ocwdd-custommessage'] ) : 'Delivery Date';
                    $ocwdd_dateformat = (!empty(sanitize_text_field( $_REQUEST['ocwdd-dateformat'] )))? sanitize_text_field( $_REQUEST['ocwdd-dateformat'] ) : 'Y/m/d';
                    $ocwdd_starting_date = sanitize_text_field($_REQUEST['ocwdd-starting_date']);
                    $ocwdd_ending_date =  sanitize_text_field($_REQUEST['ocwdd-ending_date']);
                    $ocwdd_deliverytime = sanitize_text_field($_REQUEST['ocwdd-delivery_time']);
                    $datefieldposition = sanitize_text_field($_REQUEST['datefieldposition']);
                    $timefieldposition = sanitize_text_field($_REQUEST['timefieldposition']); 
                    $ocwdd_time_suffix = sanitize_text_field($_REQUEST['ocwdd_time_suffix']);
                    $ocwdd_delivery_fee_amount =sanitize_text_field($_REQUEST['ocwdd_delivery_fee_amount']);
                    $ocwdd_fee_option_name =sanitize_text_field($_REQUEST['ocwdd_fee_option_name']);
                    update_option('ocwdd_fee_option_name' ,$ocwdd_fee_option_name ,'yes' );

                    update_option('ocwdd_delivery_fee_amount' ,$ocwdd_delivery_fee_amount ,'yes' );
                    update_option('ocwdd_time_suffix' ,$ocwdd_time_suffix ,'yes' );
                    update_option('ocwdd_enabled',$ocwdd_enabled, 'yes');
                    update_option('ocwdd_delivery_fee',$ocwdd_delivery_fee, 'yes');
                    update_option('ocwdd_deliverytime_enableordisable',$ocwdd_deliverytime_enableordisable, 'yes');
                    update_option('ocwdd_deliverydate_required',$ocwdd_deliverydate_required, 'yes');
                    update_option('ocwdd_deliverydate_enableordisable',$ocwdd_deliverydate_enableordisable, 'yes');
                    update_option('ocwdd_deliverytime_required' , $ocwdd_deliverytime_required , 'yes');
                    update_option('ocwdd_workday',$this->recursive_sanitize_text_field($_REQUEST['ocwdd-workday']), 'yes');
                    update_option('ocwdd_custommessage',$ocwdd_custommessage, 'yes');
                    update_option('datefieldposition',$datefieldposition, 'yes');
                    update_option('timefieldposition',$timefieldposition, 'yes');
                    update_option('ocwdd_deliverytime',$ocwdd_deliverytime, 'yes');
                    update_option('ocwdd_dateformat',$ocwdd_dateformat, 'yes');
                    update_option('ocwdd_starting_date',$ocwdd_starting_date, 'yes');
                    update_option('ocwdd_ending_date',$ocwdd_ending_date, 'yes');
                    update_option('ocwdd_shipping_method',$this->recursive_sanitize_text_field($_REQUEST['ocwdd_shipping_method']), 'yes');
                    update_option('delivery_time_option', $this->recursive_sanitize_text_field($_REQUEST['delivery_time_option']), 'yes' );
                    wp_redirect( admin_url( 'admin.php?page=oc-delivery-date&message=success'));
                    exit;
                }
            }
        }
    }


    function OCWDD_support_and_rating_notice() {
        $screen = get_current_screen();
       // print_r($screen);
        if( 'woocommerce_page_oc-delivery-date' == $screen->base) {
            ?>
            <div class="ocwdd_ratess_open">
                <div class="ocwdd_rateus_notice">
                    <div class="ocwdd_rtusnoti_left">
                        <h3>Rate Us</h3>
                        <label>If you like our plugin, </label>
                        <a target="_blank" href="#">
                            <label>Please vote us</label>
                        </a>
                        <label>,so we can contribute more features for you.</label>
                    </div>
                    <div class="ocwdd_rtusnoti_right">
                        <img src="<?php echo OCWDD_PLUGIN_DIR;?>/assets/images/review.png" class="ocwdd_review_icon">
                    </div>
                </div>
                <div class="ocwdd_support_notice">
                    <div class="ocwdd_rtusnoti_left">
                        <h3>Having Issues?</h3>
                        <label>You can contact us at</label>
                        <a target="_blank" href="https://oceanwebguru.com/contact-us/">
                            <label>Our Support Forum</label>
                        </a>
                    </div>
                    <div class="ocwdd_rtusnoti_right">
                        <img src="<?php echo OCWDD_PLUGIN_DIR;?>/assets/images/support.png" class="ocwdd_review_icon">
                    </div>
                </div>
            </div>
            <div class="ocwdd_donate_main">
               <img src="<?php echo OCWDD_PLUGIN_DIR;?>/assets/images/coffee.svg">
               <h3>Buy me a Coffee !</h3>
               <p>If you like this plugin, buy me a coffee and help support this plugin !</p>
               <div class="ocwdd_donate_form">
                    <a class="button button-primary ocwg_donate_btn" href="https://www.paypal.com/paypalme/shayona163/" data-link="https://www.paypal.com/paypalme/shayona163/" target="_blank">Buy me a coffee !</a>
               </div>
            </div>
            <?php
        }
    }

    function init() {
        add_action( 'admin_menu',  array($this, 'OCWDD_submenu_page'));
        add_action( 'admin_init',  array($this, 'OCWDD_save_options'));
        add_action( 'admin_notices', array($this, 'OCWDD_support_and_rating_notice' ));
        add_action( 'wp_ajax_nopriv_ocwdd_roles_ajax',array($this, 'ocwdd_role_ajax') );
        add_action( 'wp_ajax_ocwdd_roles_ajax', array($this, 'ocwdd_role_ajax') );
    }

    public static function OCWDD_instance() {
      if (!isset(self::$OCWDD_instance)) {
        self::$OCWDD_instance = new self();
        self::$OCWDD_instance->init();
      }
      return self::$OCWDD_instance;
    }

  }

  OCWDD_admin_settings::OCWDD_instance();
}

