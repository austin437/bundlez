<?php

/**
 * @array memberpress_memberships
 * @array bundlez_cm_memberships
 * @array bundlez_cc_memberships
 * 
 */



 ?>

<?php if( isset($_GET['created']) && (int) $_GET['created'] === 1) { ?>

    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Bundle Added!', 'sample-text-domain' ); ?></p>
    </div>

<?php } ?>

<?php if( isset($_GET['deleted']) && (int) $_GET['deleted'] === 1) { ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( 'Bundle Deleted!', 'sample-text-domain' ); ?></p>
    </div>
<?php } ?>

<h1>Bundlez</h1>
<hr />

<h2>Existing Bundles</h2>
<hr />

<?php foreach( $bundlez_options as $key => $bundlez_option ) { ?>
     <table class="bundlez-form-table">
        <tr valign="top">
            <th scope="row">Bundle Name</th>
            <td><?php echo $bundlez_option['bundle_name']; ?></td>
            <th scope="row">Bundle Membership</th>
            <td><?php echo $bundlez_option['bundle_membership']['membership_title']; ?></td>
        
        </tr>
      
        <tr valign="top">
            <th scope="row">Conquer Maths Membership</th>
            <td>
                <?php echo $bundlez_option['cm']['membership_title']; ?>
            </td>
            <th scope="row">CM Value</th>
            <td><?php echo $bundlez_option['cm']['membership_amount']; ?></td>        
        </tr>  
        <tr valign="top">
            <th scope="row">Conquer Computing Membership</th>
            <td>
                <?php echo $bundlez_option['cc']['membership_title']; ?>
            </td>
            <th scope="row">CC Value</th>
            <td><?php echo $bundlez_option['cc']['membership_amount']; ?></td>  
        
        </tr>  
        <tr valign="top">
            <th scope="row">
                <form onsubmit="return confirm('Do you really want to delete this bundle?');" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                    <?php echo wp_nonce_field( 'delete-bundle' ); ?>
                    <input type="hidden" name="action" value="bundlez_delete_bundle">
                    <input type="hidden" id="bundle_key" name="bundle_key" value="<?php echo $key; ?>"  />
                    <?php  submit_button( __( 'Delete', 'textdomain' ), 'delete' );?>
                </form>
            </th>
            <td><th scope="row"></th>
            <td></td>          
        </tr>  
    </table>
    <hr />

<?php } ?>

<h2>Add a new Bundle</h2>
<hr />

<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
    <?php echo wp_nonce_field( 'add-new-bundle' ); ?>
    <input type="hidden" name="action" value="bundlez_add_new_bundle">
    <table class="bundlez-form-table form-table">
        <tr valign="top">
            <th scope="row">Bundle Name</th>
            <td><input required class="bundlez-form-input" type="text" id="bundle_name" name="bundlez[bundle_name]" value="" placeholder="Bundle Name" /></td>
            <th scope="row"></th>
            <td></td>
        
        </tr>
        <tr valign="top">
            <th scope="row">Bundlez Membership</th>
            <td>
                <select required class="bundlez-form-input" id="bundle_membership_id" name="bundlez[bundle_membership]" >
                    <option value="" ></option>
                    <?php foreach( $memberpress_memberships as $membership ) { ?>
                        <option 
                            value="<?php echo $membership->ID . '|' .  $membership->post_title; ?>" 
                        >
                            <?php echo $membership->post_title; ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
            <th scope="row"></th>
            <td></td>
        
        </tr>  
        <tr valign="top">
            <th scope="row">Conquer Maths Membership</th>
            <td>
                <select required class="bundlez-form-input" id="cm_membership_id" name="bundlez[cm][membership]">
                    <option value=""></option>
                    <?php
                        foreach( $bundlez_cm_memberships as $bundlez_cm_membership ){
                            ?>
                                <option 
                                    value="<?php echo $bundlez_cm_membership->id ."|".$bundlez_cm_membership->title; ?>"
                                >   
                                    <?php echo $bundlez_cm_membership->title; ?>
                                </option>
                            <?php
                        }
                    ?>
                </select>
            </td>
            <th scope="row">CM Value</th>
            <td><input required class="bundlez-form-input" type="number" min="0" id="cm_membership_amount" name="bundlez[cm][membership_amount]" value="" placeholder="Enter an amount" /></td>
        
        </tr>  
        <tr valign="top">
            <th scope="row">Conquer Computing Membership</th>
            <td>
                <select required class="bundlez-form-input" id="cc_membership_id" name="bundlez[cc][membership]">
                    <option value=""></option>
                    <?php
                        foreach( $bundlez_cc_memberships as $bundlez_cc_membership ){
                            ?>
                                <option 
                                    value="<?php echo $bundlez_cc_membership->id . "|" . $bundlez_cc_membership->title; ?>"
                                >
                                    <?php echo $bundlez_cc_membership->title; ?>
                                </option>
                            <?php
                        }
                    ?>
                </select>
            </td>
            <th scope="row">CC Value</th>
            <td><input required class="bundlez-form-input" type="number" min="0" id="cc_membership_amount" name="bundlez[cc][membership_amount]" value="" placeholder="Enter an amount" /></td>
        
        </tr>  
    </table>
    <?php  submit_button(); ?>
</form>