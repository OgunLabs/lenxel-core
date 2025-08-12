<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="modal fade modal-ajax-user-form" id="form-ajax-login-popup" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
            <div class="modal-header-form">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
         <div class="modal-body">
            <div class="ajax-user-form">
               <h2 class="title"><?php echo esc_html__('Signin', 'lenxel-core'); ?></h2>
               <div class="form-ajax-login-popup-content">
                  <?php 
                     if(class_exists('Lenxel_Addons_Login_Ajax')){
                        Lenxel_Addons_Login_Ajax::instance()->html_form();
                     } 
                  ?>
               </div>
               <div class="user-registration">
                  <?php echo esc_html__("Don't have an account", "lenxel-core"); ?>
                  <a class="registration-popup" data-toggle="modal" data-target="#form-ajax-registration-popup">
                     <?php echo esc_html__('Register', 'lenxel-core') ?>
                  </a>
               </div>   
            </div>   
         </div>
      </div>
   </div>
</div>

<div class="modal fade modal-ajax-user-form" id="form-ajax-lost-password-popup" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header-form">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="ajax-user-form">
               <h2 class="title"><?php echo esc_html__('Reset Password', 'lenxel-core'); ?></h2>
               <div class="form-ajax-login-popup-content">
                  <?php
                     if(class_exists('Lenxel_Addons_Forget_Pwd_Ajax')){
                         Lenxel_Addons_Forget_Pwd_Ajax::instance()->html_form();
                     } 
                  ?>
               </div>
             
               <div class="user-registration">
                  <?php echo esc_html__("Don't have an account", "lenxel-core"); ?>
                  <?php if(class_exists('uListing\Classes\StmUser')){ ?>
                     <a class="registration-popup" href="<?php echo uListing\Classes\StmUser::getProfileUrl() ?>?tab=register">
                        <?php echo esc_html__('Register', 'lenxel-core') ?>
                     </a>
                  <?php }else{ ?>
                     <a class="registration-popup" data-toggle="modal" data-target="#form-ajax-registration-popup">
                        <?php echo esc_html__('Register', 'lenxel-core') ?>
                     </a>
                  <?php } ?>   
               </div>   

            </div>   
         </div>
      </div>
   </div>
</div>
