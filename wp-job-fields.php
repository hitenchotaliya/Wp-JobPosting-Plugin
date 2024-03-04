<?php

function dwwp_add_custom_metabox()
{
    add_meta_box(
        'dwwp_meta',
        'Job Listing',
        'dwwp_meta_callback',
        'job',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'dwwp_add_custom_metabox');

function dwwp_meta_callback($post)
{
    wp_nonce_field(basename(__FILE__), 'dwwp_jobs_nonce');
    $dwwp_stored_metadata = get_post_meta($post->ID);
?>
    <div>
        <div class="meta-raw">
            <div class="meta-th">
                <label for="job_id" class="dwwp-raw-title">Job ID</label>
            </div>
            <div class="meta-td">
                <input type="text" name="job_id" id="job-id" value="<?php if (!empty($dwwp_stored_metadata['job_id'])) echo esc_attr($dwwp_stored_metadata['job_id'][0]); ?>" />
            </div>
        </div>
        <div class="meta-raw">
            <div class="meta-th">
                <label for="date_listed" class="dwwp-raw-title">Date Listed</label>
            </div>
            <div class="meta-td">
                <input type="text" name="date_listed" class="dwwp-raw-content datepicker" id="date-listed" value="<?php if (!empty($dwwp_stored_metadata['date_listed'])) echo esc_attr($dwwp_stored_metadata['date_listed'][0]); ?>" />
            </div>
        </div>
        <div class="meta-raw">
            <div class="meta-th">
                <label for="application_deadline" class="dwwp-raw-title">Application Deadline</label>
            </div>
            <div class="meta-td">
                <input type="text" class="dwwp-raw-content datepicker" name="application_deadline" id="application-deadline" value="<?php if (!empty($dwwp_stored_metadata['application_deadline'])) echo esc_attr($dwwp_stored_metadata['application_deadline'][0]); ?>" />
            </div>
        </div>
        <div class="meta">
            <div class="meta-th">
                <span>Principle Duties</span>
            </div>
        </div>
        <div class="meta-editor">
            <?php
            $content = get_post_meta($post->ID, 'principle_duties', true);
            $editor = 'principle_duties';
            $setting = array(
                'textarea_raw' => false,
                'media_button' => false,
            );

            wp_editor($content, $editor, $setting);
            ?>
        </div>
        <div class="meta-raw">
            <div class="meta-th">
                <label for="minimum-requirements" class="dwwp-raw-title">Minimum Requirements</label>
            </div>
            <div class="meta-td">
                <textarea type="text" name="minimum_requirements" class="dwwp-textarea" id="minimum-requirements"><?php echo !empty($dwwp_stored_metadata['minimum_requirements']) ? esc_textarea($dwwp_stored_metadata['minimum_requirements'][0]) : ''; ?></textarea>
            </div>
        </div>
        <div class="meta-raw">
            <div class="meta-th">
                <label for="preferred-requirements" class="dwwp-raw-title">Preferred Requirements</label>
            </div>
            <div class="meta-td">
                <textarea type="text" name="preferred_requirements" class="dwwp-textarea" id="preferred-requirements"><?php echo !empty($dwwp_stored_metadata['preferred_requirements']) ? esc_textarea($dwwp_stored_metadata['preferred_requirements'][0]) : ''; ?></textarea>
            </div>
        </div>
        <div class="meta-raw">
            <div class="meta-th">
                <label for="relocation-assistance" class="dwwp-raw-title">Relocation Assistance</label>
            </div>
            <div class="meta-td">
                <select name="relocation_assistance" id="relocation-assistance">
                    <option value="select-yes" <?php selected('select-yes', !empty($dwwp_stored_metadata['relocation_assistance']) ? $dwwp_stored_metadata['relocation_assistance'][0] : ''); ?>>Yes</option>
                    <option value="select-no" <?php selected('select-no', !empty($dwwp_stored_metadata['relocation_assistance']) ? $dwwp_stored_metadata['relocation_assistance'][0] : ''); ?>>No</option>
                </select>
            </div>
        </div>
    </div>
<?php
}

function dwwp_meta_save($post_id)
{
    //Check save status
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['dwwp_jobs_nonce']) && wp_verify_nonce($_POST['dwwp_jobs_nonce'], basename(__FILE__))) ? 'true' : 'false';

    //Exist
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }

    if (isset($_POST['job_id'])) {
        update_post_meta($post_id, 'job_id', sanitize_text_field($_POST['job_id']));
    }
    if (isset($_POST['date_listed'])) {
        update_post_meta($post_id, 'date_listed', sanitize_text_field($_POST['date_listed']));
    }
    if (isset($_POST['application_deadline'])) {
        update_post_meta($post_id, 'application_deadline', sanitize_text_field($_POST['application_deadline']));
    }
    if (isset($_POST['minimum_requirements'])) {
        update_post_meta($post_id, 'minimum_requirements', sanitize_textarea_field($_POST['minimum_requirements']));
    }
    if (isset($_POST['preferred_requirements'])) {
        update_post_meta($post_id, 'preferred_requirements', sanitize_textarea_field($_POST['preferred_requirements']));
    }
    if (isset($_POST['relocation_assistance'])) {
        update_post_meta($post_id, 'relocation_assistance', sanitize_text_field($_POST['relocation_assistance']));
    }
}

add_action('save_post', 'dwwp_meta_save');
