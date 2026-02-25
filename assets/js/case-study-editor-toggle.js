(function ($) {
    function getTypeValue() {
      // ACF select field: name="acf[field_key]" in DOM
      // We target by the field key to be reliable
      var $field = $('[data-key="field_jmdc_case_study_type"] select');
      return $field.length ? $field.val() : 'internal';
    }
  
    function toggleEditor() {
      var type = getTypeValue();
      var isInternal = type === 'internal';
  
      // Block editor wrapper
      var $editor = $('#editor');
  
      // If not found (classic), try alternative selectors
      if (!$editor.length) {
        $editor = $('.block-editor');
      }
  
      // Add a notice above editor
      var $notice = $('.jmdc-editor-notice');
      if (!$notice.length) {
        $notice = $('<div class="notice notice-info jmdc-editor-notice"><p><strong>Editor hidden:</strong> Set Type to <em>Internal Page</em> to edit page content.</p></div>');
        // Place it near ACF fields / top of page
        $('#poststuff').prepend($notice);
      }
  
      if (isInternal) {
        $notice.hide();
        $editor.show();
      } else {
        $notice.show();
        $editor.hide();
      }
    }
  
    // Run on load and when ACF changes
    $(document).ready(function () {
      toggleEditor();
  
      // ACF triggers events when fields change
      if (window.acf) {
        window.acf.addAction('change', function ($el) {
          if ($el && $el.closest('[data-key="field_jmdc_case_study_type"]').length) {
            toggleEditor();
          }
        });
      }
  
      // Also bind directly to select
      $(document).on('change', '[data-key="field_jmdc_case_study_type"] select', toggleEditor);
    });
  })(jQuery);