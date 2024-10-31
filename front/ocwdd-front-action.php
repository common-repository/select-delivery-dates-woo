<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCWDD_front_action')) {

  class OCWDD_front_action {

    /**
    * Constructor.
    *
    * @version 3.2.3
    */
    public $ocwdd_message,$ocwdd_dateformat,$ocwdd_ed_select_offday,$ocwdd_workday,$ocwdd_starting_date,$ocwdd_ending_date,$ocwdd_deliverydate_required , $ocwdd_deliverytime_required,$ocwdd_deliverytime;

    function __construct() {
        //Enable Plugin
        $user = wp_get_current_user();
        $roles = $user->roles;
        $user_roles = get_option('ocwdd_roles_select');
        $ocwdd_display_for_u_role= get_option('ocwdd_display_for_u_role');

        if ( 'yes' === get_option( 'ocwdd_enabled') ) {
            //get or set custom messsage
            if ( !empty(get_option('ocwdd_custommessage')) ) {
                $this->ocwdd_message = get_option('ocwdd_custommessage'); 
            }

            //get or set date format
            if ( !empty(get_option('ocwdd_dateformat')) ) {
                $this->ocwdd_dateformat = get_option('ocwdd_dateformat'); 
            }

            //get or set display disable
            if ( !empty(get_option('ocwdd_ed_select_offday')) ) {
                $this->ocwdd_ed_select_offday = get_option('ocwdd_ed_select_offday'); 
            }

            //get or set work day
            if ( !empty(get_option('ocwdd_workday')) ) {
                $this->ocwdd_workday = get_option('ocwdd_workday'); 
            }

             //get or set work day
            if ( !empty(get_option('ocwdd_starting_date')) ) {
                $this->ocwdd_starting_date = get_option('ocwdd_starting_date'); 
            }

             //get or set work day
            if ( !empty(get_option('ocwdd_ending_date')) ) {
                $this->ocwdd_ending_date = get_option('ocwdd_ending_date'); 
            }

            //set delivery date field required or not required
            if ( !empty(get_option('ocwdd_deliverydate_required')) ) {
                $this->ocwdd_deliverydate_required = get_option('ocwdd_deliverydate_required'); 
            }

            //set delivery time required or not 
            if ( !empty(get_option('ocwdd_deliverytime_required')) ) {
                $this->ocwdd_deliverytime_required = get_option('ocwdd_deliverytime_required'); 
            }

            //set delivery date label
            if ( !empty(get_option('ocwdd_custommessage')) ) {
                $this->ocwdd_custommessage = get_option('ocwdd_custommessage');
            } else {
                $this->ocwdd_custommessage = 'Delivery Date';
            }

            //set delivery date label
            if ( !empty(get_option('ocwdd_deliverytime')) ) {
                $this->ocwdd_deliverytime = get_option('ocwdd_deliverytime');
            } else {
                $this->ocwdd_deliverytime = 'Delivery Time';
            }


            if ($ocwdd_display_for_u_role == 'yes') {
                if (isset($roles[0]) && !empty($user_roles)) {
                    if (in_array($roles[0], $user_roles)) {
                        $datefieldposition= get_option('datefieldposition');
                        if($datefieldposition == 'before_notes'){
                            add_action('woocommerce_before_order_notes', array($this,'ocwdd_checkout_field'), 10, 1 );
                        }else if($datefieldposition == 'after_billing'){
                            add_action('woocommerce_after_checkout_billing_form', array($this,'ocwdd_checkout_field'), 10, 1 );
                        }else if($datefieldposition == 'before_billing'){
                            add_action('woocommerce_before_checkout_billing_form', array($this,'ocwdd_checkout_field'), 10, 1 );
                        }else{
                            add_action('woocommerce_after_order_notes', array($this,'ocwdd_checkout_field'), 10, 1 );
                        }


                        $timefieldposition = get_option('timefieldposition');
                        if($timefieldposition == 'before_notes'){
                            add_action('woocommerce_before_order_notes', array($this,'my_custom_checkouttime_field'), 10, 1 );
                        }else if($timefieldposition == 'after_billing'){
                            add_action('woocommerce_after_checkout_billing_form', array($this,'my_custom_checkouttime_field'), 10, 1 );
                        }else if($timefieldposition == 'before_billing'){
                            add_action('woocommerce_before_checkout_billing_form', array($this,'my_custom_checkouttime_field'), 10, 1 );
                        }else{
                            add_action('woocommerce_after_order_notes', array($this,'my_custom_checkouttime_field'), 10, 1 );
                        }

                        // add_action('woocommerce_after_order_notes', array($this,'my_custom_checkouttime_field') , 10 , 2 );
                        add_action('woocommerce_checkout_update_order_meta', array($this,'ocwdd_save_extra_checkout_fields'));
                        add_action('woocommerce_checkout_update_order_meta', array($this,'ocwdd_save_extra_checkouttime_fields'));
                        add_action('woocommerce_checkout_process', array($this,'ocwdd_checkout_field_process'));
                        add_action('woocommerce_checkout_process', array($this,'ocwdd_checkouttime_field_process'));
                        add_action('woocommerce_thankyou', array($this,'ocwdd_display_order_data'), 1 );
                        add_action('woocommerce_view_order', array($this,'ocwdd_display_order_data'), 1 );
                        add_action('woocommerce_thankyou', array($this,'ocwdd_display_ordertime_data'), 1 );
                        add_action('woocommerce_view_order', array($this,'ocwdd_display_ordertime_data'), 1 );
                        add_action('woocommerce_admin_order_data_after_order_details', array($this,'ocwdd_display_order_data_in_admin') );
                        add_action('woocommerce_admin_order_data_after_order_details', array($this,'ocwdd_display_ordertime_data_in_admin') );
                        add_action('woocommerce_email_customer_details', array($this,'ocwdd_show_email_order_meta'), 10, 4 );
                        add_action('woocommerce_email_customer_details', array($this,'ocwdd_show_email_ordertime_meta'), 10, 4 );
                    }
                }
            }else{
                $datefieldposition= get_option('datefieldposition');
                if($datefieldposition == 'before_notes'){
                    add_action('woocommerce_before_order_notes', array($this,'ocwdd_checkout_field'), 10, 1 );
                }else if($datefieldposition == 'after_billing'){
                    add_action('woocommerce_after_checkout_billing_form', array($this,'ocwdd_checkout_field'), 10, 1 );
                }else if($datefieldposition == 'before_billing'){
                    add_action('woocommerce_before_checkout_billing_form', array($this,'ocwdd_checkout_field'), 10, 1 );
                }else{
                    add_action('woocommerce_after_order_notes', array($this,'ocwdd_checkout_field'), 10, 1 );
                }


                $timefieldposition = get_option('timefieldposition');
                if($timefieldposition == 'before_notes'){
                    add_action('woocommerce_before_order_notes', array($this,'my_custom_checkouttime_field'), 10, 1 );
                }else if($timefieldposition == 'after_billing'){
                    add_action('woocommerce_after_checkout_billing_form', array($this,'my_custom_checkouttime_field'), 10, 1 );
                }else if($timefieldposition == 'before_billing'){
                    add_action('woocommerce_before_checkout_billing_form', array($this,'my_custom_checkouttime_field'), 10, 1 );
                }else{
                    add_action('woocommerce_after_order_notes', array($this,'my_custom_checkouttime_field'), 10, 1 );
                }

                // add_action('woocommerce_after_order_notes', array($this,'my_custom_checkouttime_field') , 10 , 2 );
                add_action('woocommerce_checkout_update_order_meta', array($this,'ocwdd_save_extra_checkout_fields'));
                add_action('woocommerce_checkout_update_order_meta', array($this,'ocwdd_save_extra_checkouttime_fields'));
                add_action('woocommerce_checkout_process', array($this,'ocwdd_checkout_field_process'));
                add_action('woocommerce_checkout_process', array($this,'ocwdd_checkouttime_field_process'));
                add_action('woocommerce_thankyou', array($this,'ocwdd_display_order_data'), 1 );
                add_action('woocommerce_view_order', array($this,'ocwdd_display_order_data'), 1 );
                add_action('woocommerce_thankyou', array($this,'ocwdd_display_ordertime_data'), 1 );
                add_action('woocommerce_view_order', array($this,'ocwdd_display_ordertime_data'), 1 );
                add_action('woocommerce_admin_order_data_after_order_details', array($this,'ocwdd_display_order_data_in_admin') );
                add_action('woocommerce_admin_order_data_after_order_details', array($this,'ocwdd_display_ordertime_data_in_admin') );
                add_action('woocommerce_email_customer_details', array($this,'ocwdd_show_email_order_meta'), 10, 4 );
                add_action('woocommerce_email_customer_details', array($this,'ocwdd_show_email_ordertime_meta'), 10, 4 );
            }
        }
    }

    protected static $OCWDD_instance;
    /**
     * Registers ADL Post Slider post type.
     */
    // define the woocommerce_after_cart_item_name callback 

    /*Delivery Date start*/

    function ocwdd_display_order_data($order_id){ 

        $ocwdd_deliverydate = get_post_meta( $order_id, '_ocwdd_deliverydate', true );

        if(!empty($ocwdd_deliverydate)){ ?>
            <p>
              <strong>
                <?php echo $this->ocwdd_message; ?>:
              </strong>
                <?php echo $ocwdd_deliverydate;?>
            </p> 
        <?php 
        }
    }


    function ocwdd_display_order_data_in_admin( $order ){  ?>

        <?php
            if(!empty(get_post_meta( $order->get_id(), '_ocwdd_deliverydate', true ))){
                echo '<div class="form-field form-field-wide ocwdd_delivery_date_admin"><strong>' . $this->ocwdd_message . ': </strong>' . get_post_meta( $order->get_id(), '_ocwdd_deliverydate', true ) . '</div>';
            }
        ?>

        <?php 
    }


  
    function ocwdd_show_email_order_meta( $order, $sent_to_admin, $plain_text ) {

        $ocwdd_deliverydates = get_post_meta( $order->id, '_ocwdd_deliverydate', true );

            if(!empty($ocwdd_deliverydates)){

                if( $plain_text ){

                    echo $this->ocwdd_message.' '. $ocwdd_deliverydates;
                    
                } else {

                echo '<p><strong>'.$this->ocwdd_message.'</strong>: ' . $ocwdd_deliverydates . '</p>';

            }
        }
    }



    function ocwdd_checkout_field($checkout){
        $ocwdd_deliverydate_enableordisable = get_option( 'ocwdd_deliverydate_enableordisable' );
            if( $ocwdd_deliverydate_enableordisable == "true"){
                woocommerce_form_field( 'ocwdd_deliverydate', array(
                'type'          => 'text',
                'class'         => array('my-field-class form-row-wide'),
                'id'            => 'ocwdd_deliverydate',
                'label'         => $this->ocwdd_custommessage,
                'required'      => $this->ocwdd_deliverydate_required,
                'placeholder'   => $this->ocwdd_dateformat,
                ),$checkout->get_value( 'ocwdd_deliverydate')
            );
        }
    }



    function ocwdd_checkout_field_process() {

        if($this->ocwdd_deliverydate_required == 'true'){

            if ( ! $_POST['ocwdd_deliverydate'] ){

                wc_add_notice( __( '<strong>'.$this->ocwdd_custommessage.'</strong> is a required field.' ), 'error' );

            }
        }

    }


    function ocwdd_save_extra_checkout_fields( $order_id){
        if( !empty( $_POST['ocwdd_deliverydate'] ) ) {
            update_post_meta( $order_id, '_ocwdd_deliverydate', sanitize_text_field( $_POST['ocwdd_deliverydate'] ) );
        } 
    }

    function my_custom_checkouttime_field( $checkout ) {
        $delivery_time_option  = get_option('delivery_time_option');
        $new_array_time['0']= 'choose time';
        foreach($delivery_time_option as $key => $val) {
            $new_array_time[$val]=$val.' '.stripslashes(get_option( 'ocwdd_time_suffix' ));
        }

        if(get_option( 'ocwdd_deliverytime_enableordisable' ) == 'true'){
            woocommerce_form_field( 'ocowdd_deliverytime', array(
            'type'          => 'select',
            'class'         =>array('my-field-class form-row-wide'),
            'label'         =>$this->ocwdd_deliverytime,
            'options'       => $new_array_time,
            'required'      => $this->ocwdd_deliverytime_required,
            ),$checkout->get_value('ocowdd_deliverytime'));
        }
    }



    function ocwdd_checkouttime_field_process() {
        if($this->ocwdd_deliverytime_required == 'true'){
            if ($_POST['ocowdd_deliverytime'] == "0"){
                wc_add_notice( __( '<strong>'.$this->ocwdd_deliverytime.'</strong> is a required field.' ), 'error' );
            }
        }
    }



    function ocwdd_save_extra_checkouttime_fields( $order_id){
        if( !empty( $_POST['ocowdd_deliverytime'] ) ) {
            update_post_meta( $order_id, '_ocwdd_deliverytime', sanitize_text_field( $_POST['ocowdd_deliverytime'] ) );
        } 
    }


    function ocwdd_display_ordertime_data($order_id){ 
        $ocwdd_deliverytime = get_post_meta( $order_id, '_ocwdd_deliverytime', true );
        if(!empty($ocwdd_deliverytime)){ ?>
            <p>
              <strong>
                <?php echo $this->ocwdd_deliverytime; ?>:
              </strong>
                <?php echo $ocwdd_deliverytime.' '.stripslashes(get_option( 'ocwdd_time_suffix' ));?>
            </p>
        <?php 
        }
    }


    function ocwdd_display_ordertime_data_in_admin( $order ){  
        if(!empty(get_post_meta( $order->get_id(), '_ocwdd_deliverytime', true ))){
            echo '<div class="form-field form-field-wide ocwdd_delivery_date_admin"><strong>' . $this->ocwdd_deliverytime . ': </strong>' . get_post_meta( $order->get_id(), '_ocwdd_deliverytime', true ) .' '.stripslashes(get_option( 'ocwdd_time_suffix' )). '</div>';
        }
    }


    function ocwdd_show_email_ordertime_meta( $order, $sent_to_admin, $plain_text ) {
        $ocwdd_deliverytime = get_post_meta( $order->id, '_ocwdd_deliverytime', true );
        if(!empty($ocwdd_deliverytime)){
            if( $plain_text ){
                echo $this->ocwdd_deliverytime.' '. $ocwdd_deliverytime;
            } else {
                echo '<p><strong>'.$this->ocwdd_deliverytime.'</strong>: ' . $ocwdd_deliverytime .' '.stripslashes(get_option( 'ocwdd_time_suffix' )) .'</p>';
            }
        }
    }


    /* Delivery Time END */
    function ocwdd_footer_custom_script(){
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("#ocwdd_deliverydate").attr('autocomplete','off');
                var date_string = "<?php echo $this->ocwdd_dateformat;?>";
                var ocwdd_ed_select_offday = "<?php echo $this->ocwdd_ed_select_offday;?>";
                var delivery_date = "<?php echo $this->ocwdd_starting_date;?>";
                var delivery_date_end = "<?php echo $this->ocwdd_ending_date;?>";
                var ocwdd_workday = <?php echo json_encode($this->ocwdd_workday, true);?>;
                var d_date_f = new Date(delivery_date);
                var d_date_l = new Date(delivery_date_end); 
                var date_format = date_string.replace("Y", "yy").replace('m', 'mm').replace('d', 'dd');            
                between = [];

                while (d_date_f <= d_date_l) {
                    fristdate = d_date_f.getDate() + "-" + (d_date_f.getMonth()+1) + "-" + d_date_f.getFullYear();
                    var day = d_date_f.getDay();
                    if (jQuery.inArray('', ocwdd_workday) != -1 || ocwdd_workday == null){
                        between.push(fristdate); 
                    } else if (jQuery.inArray(day.toString(), ocwdd_workday) != -1) {
                        between.push(fristdate);
                    }
                    d_date_f.setDate(d_date_f.getDate() + 1);
                }
                

                function available(date) {
                    defultdate = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
                    if (jQuery.inArray(defultdate, between) != -1 ) {
                        return [true, "","Available"];
                    } else {
                        return [false,"","unAvailable"];
                    }
                }

                function availabledays(date) {
                    var day = date.getDay();
                    if (jQuery.inArray(day.toString(), ocwdd_workday) != -1) {
                        return [true, "","Available"];
                    } else {
                        return [false,"","unAvailable"];
                    }
                }

                if(delivery_date == '' && between == '' && (jQuery.inArray('', ocwdd_workday) != -1 || ocwdd_workday == null)){
                
                    jQuery( "#ocwdd_deliverydate" ).datepicker({
                        dateFormat: date_format,
                        minDate: 0
                    });
                } else if(delivery_date != '' && between == '' && (jQuery.inArray('', ocwdd_workday) != -1 || ocwdd_workday == null)){
                
                    jQuery( "#ocwdd_deliverydate" ).datepicker({
                        dateFormat: date_format,
                        minDate: new Date(d_date_f)
                    });
                } else if(delivery_date != '' && between == '' && (jQuery.inArray('', ocwdd_workday) == -1 || ocwdd_workday != null)){
               
                    jQuery( "#ocwdd_deliverydate" ).datepicker({
                        dateFormat: date_format,
                        minDate: new Date(d_date_f),
                        beforeShowDay: availabledays
                    });
                } else if(delivery_date == '' && between == '' && (jQuery.inArray('', ocwdd_workday) == -1 || ocwdd_workday != null)){
               
                    jQuery( "#ocwdd_deliverydate" ).datepicker({
                        dateFormat: date_format,
                        minDate: 0,
                        beforeShowDay: availabledays
                    });
                } else { 
                    jQuery( "#ocwdd_deliverydate" ).datepicker({
                        dateFormat: date_format,
                        minDate: 0,
                        maxDate: new Date(d_date_l),
                        beforeShowDay: available
                    });
                }
            })
        </script>
    <?php  
    }

    function ocwdd_wc_add_surcharge() { 
        global $woocommerce; 
           if ( is_admin() && ! defined( 'DOING_AJAX' ) ) 
            return;
        $ocwdd_delivery_fee = get_option( 'ocwdd_delivery_fee' );

        if($ocwdd_delivery_fee == "yes"){
            $method_names     = array();
                $chosen_methods = WC()->session->get( 'chosen_shipping_methods', array() );
                
                    foreach ( $chosen_methods as $chosen_method ) {
                        $chosen_method = explode( ':', $chosen_method );
                        $method_names[]  = current( $chosen_method );
                    }
         
            $ocwdd_shipping_methods = get_option('ocwdd_shipping_method');
            $ocwdd_delivery_fee_amount= get_option('ocwdd_delivery_fee_amount');
            $ocwdd_fee_option_name= get_option('ocwdd_fee_option_name');
            if(!empty($ocwdd_shipping_methods)){
                if(!empty($method_names) && in_array($method_names[0],$ocwdd_shipping_methods)){
                  $fee = $ocwdd_delivery_fee_amount;
                  $woocommerce->cart->add_fee( $ocwdd_fee_option_name, $fee, true, 'standard' );  
                }
            }
        }
        
    }

    function init() {
        add_action( 'wp_footer',  array($this, 'ocwdd_footer_custom_script'));
        add_action( 'woocommerce_cart_calculate_fees',array($this,'ocwdd_wc_add_surcharge'));
    }
         

    public static function OCWDD_instance() {
        if (!isset(self::$OCWDD_instance)) {
            self::$OCWDD_instance = new self();
            self::$OCWDD_instance->init();
        }
      return self::$OCWDD_instance;
    }

  }

  OCWDD_front_action::OCWDD_instance();
}

?>
