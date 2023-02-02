<?php

if (!class_exists('GFForms')) {
    die();
}

class FIFU_GF_Image_Field extends GF_Field {

    /**
     * @var string $type The field type.
     */
    public $type = 'fifu_image';

    /**
     * Return the field title, for use in the form editor.
     *
     * @return string
     */
    public function get_form_editor_field_title() {
        return esc_attr__('Featured Image (FIFU)', 'fifufieldaddon');
    }

    /**
     * Assign the field button to the Post Fields group.
     *
     * @return array
     */
    public function get_form_editor_button() {
        return array(
            'group' => 'post_fields',
            'text' => $this->get_form_editor_field_title(),
            'icon' => 'dashicons-camera',
        );
    }

    /**
     * The settings which should be available on the field in the form editor.
     *
     * @return array
     */
    function get_form_editor_field_settings() {
        return array(
            'label_setting',
            'description_setting',
            'rules_setting',
            'placeholder_setting',
            'input_class_setting',
            'css_class_setting',
            'size_setting',
            'admin_label_setting',
            'default_value_setting',
            'visibility_setting',
            'conditional_logic_field_setting',
        );
    }

    /**
     * Enable this field for use with conditional logic.
     *
     * @return bool
     */
    public function is_conditional_logic_supported() {
        return true;
    }

    /**
     * The scripts to be included in the form editor.
     *
     * @return string
     */
    public function get_form_editor_inline_script_on_page_render() {

        // set the default field label for the simple type field
        $script = sprintf("function SetDefaultValues_simple(field) {field.label = '%s';}", $this->get_form_editor_field_title()) . PHP_EOL;

        // initialize the fields custom settings
        $script .= "jQuery(document).bind('gform_load_field_settings', function (event, field, form) {" .
                "var inputClass = field.inputClass == undefined ? '' : field.inputClass;" .
                "jQuery('#input_class_setting').val(inputClass);" .
                "});" . PHP_EOL;

        // saving the simple setting
        $script .= "function SetInputClassSetting(value) {SetFieldProperty('inputClass', value);}" . PHP_EOL;

        return $script;
    }

    /**
     * Define the fields inner markup.
     *
     * @param array $form The Form Object currently being processed.
     * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
     * @param null|array $entry Null or the Entry Object currently being edited.
     *
     * @return string
     */
    public function get_field_input($form, $value = '', $entry = null) {
        $id = absint($this->id);
        $form_id = absint($form['id']);
        $is_entry_detail = $this->is_entry_detail();
        $is_form_editor = $this->is_form_editor();

        // Prepare the value of the input ID attribute.
        $field_id = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

        $value = esc_attr($value);

        // Get the value of the inputClass property for the current field.
        $inputClass = $this->inputClass;

        // Prepare the input classes.
        $size = $this->size;
        $class_suffix = $is_entry_detail ? '_admin' : '';
        $class = $size . $class_suffix . ' ' . $inputClass;

        // Prepare the other input attributes.
        $tabindex = $this->get_tabindex();
        $logic_event = !$is_form_editor && !$is_entry_detail ? $this->get_conditional_logic_event('keyup') : '';
        $placeholder_attribute = 'placeholder="Image URL"';
        $required_attribute = $this->isRequired ? 'aria-required="true"' : '';
        $invalid_attribute = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';
        $disabled_text = $is_form_editor ? 'disabled="disabled"' : '';

        // Prepare the input tag for this field.
        $input = "<input name='input_{$id}' id='{$field_id}' type='text' value='{$value}' class='{$class}' {$tabindex} {$logic_event} {$placeholder_attribute} {$required_attribute} {$invalid_attribute} {$disabled_text}/>";

        return sprintf("<div class='ginput_container ginput_container_%s'>%s</div>", $this->type, $input);
    }

    public function get_value_save_entry($value, $form, $input_name, $lead_id, $lead) {
        if ($this->phoneFormat == 'standard' && preg_match('/^\D?(\d{3})\D?\D?(\d{3})\D?(\d{4})$/', $value, $matches)) {
            $value = sprintf('(%s) %s-%s', $matches[1], $matches[2], $matches[3]);
        }

        $_POST['fifu_input_url'] = $value;

        return $value;
    }

}

GF_Fields::register(new FIFU_GF_Image_Field());

