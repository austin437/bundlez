<?php

/**
 * @array memberpress_memberships
 */

 ?>

<h1>Bundlez</h1>
<hr />

<h2>Existing Bundles</h2>
<hr />


<h2>Add a new Bundle</h2>
<hr />

<form method="post" action="bundlez-add-new-bundle">
    <table class="bundlez-form-table form-table">
        <tr valign="top">
            <th scope="row">Bundle Name</th>
            <td><input class="bundlez-form-input" type="text" id="bundle_name" name="bundlez[][bundle_name]" value="" placeholder="Bundle Name" /></td>
            <th scope="row"></th>
            <td></td>
        
        </tr>
        <tr valign="top">
            <th scope="row">Bundlez Membership</th>
            <td>
                <select class="bundlez-form-input" id="bundle_membership" name="bundlez[][bundle_membership]" >
                    <option value="" ></option>
                    <?php foreach( $memberpress_memberships as $membership ) { ?>
                        <option value="<?php echo $membership->ID; ?>" ><?php echo $membership->post_title; ?></option>
                    <?php } ?>
                </select>
            </td>
            <th scope="row"></th>
            <td></td>
        
        </tr>  
    </table>
</form>