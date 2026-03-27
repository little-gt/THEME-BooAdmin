<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<!-- Field Delete Confirm Modal -->
<div id="field-delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white border border-gray-200 max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-discord-text mb-4"><?php _e('确认删除'); ?></h3>
        <p class="text-discord-muted mb-6"><?php _e('确认要删除此字段吗?'); ?></p>
        <div class="flex justify-end space-x-3">
            <button id="cancel-field-delete" class="px-4 py-2 bg-gray-200 text-discord-text font-medium hover:bg-gray-300 transition-colors text-sm">
                <?php _e('取消'); ?>
            </button>
            <button id="confirm-field-delete" class="px-4 py-2 bg-discord-accent text-white font-medium hover:bg-blue-600 transition-colors text-sm">
                <?php _e('确认删除'); ?>
            </button>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    // 自定义字段
    var fieldToDelete = null;

    function attachDeleteEvent (el) {
        $('.btn-delete', el).click(function () {
            fieldToDelete = $(this).closest('.field');
            $('#field-delete-confirm-modal').removeClass('hidden');
        });
    }

    $('#custom-field .fields .field').each(function () {
        attachDeleteEvent(this);
    });

    // Field delete modal handlers
    $('#cancel-field-delete').click(function () {
        $('#field-delete-confirm-modal').addClass('hidden');
        fieldToDelete = null;
    });

    $('#confirm-field-delete').click(function () {
        if (fieldToDelete) {
            fieldToDelete.fadeOut(function () {
                $(this).remove();
            });
        }
        $('#field-delete-confirm-modal').addClass('hidden');
        fieldToDelete = null;
    });

    // Close modal when clicking outside
    $('#field-delete-confirm-modal').click(function (e) {
        if (e.target === this) {
            $('#field-delete-confirm-modal').addClass('hidden');
            fieldToDelete = null;
        }
    });

    $('#custom-field button.operate-add').click(function () {
          var html = '<div class="field group bg-gray-50 p-3 border border-gray-200 relative hover:border-discord-accent transition-colors">'
                 + '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-2">'
                 + '<div class="md:col-span-2">'
                 + '<label class="block text-xs font-medium text-gray-500 mb-1"><?php _e('字段名称'); ?></label>'
                 + '<input type="text" name="fieldNames[]" pattern="^[_a-zA-Z][_a-zA-Z0-9]*$" oninput="this.reportValidity()" class="w-full px-2 py-1.5 bg-white border border-gray-300 text-sm focus:outline-none focus:border-discord-accent transition-colors" placeholder="<?php _e('字段名称'); ?>">'
                 + '</div>'
                 + '<div>'
                 + '<label class="block text-xs font-medium text-gray-500 mb-1"><?php _e('类型'); ?></label>'
                 + '<select name="fieldTypes[]" class="w-full px-2 py-1.5 bg-white border border-gray-300 text-sm focus:outline-none focus:border-discord-accent transition-colors">'
                 + '<option value="str"><?php _e('字符'); ?></option>'
                 + '<option value="int"><?php _e('整数'); ?></option>'
                 + '<option value="float"><?php _e('小数'); ?></option>'
                 + '<option value="json"><?php _e('JSON 结构'); ?></option>'
                 + '</select>'
                 + '</div>'
                 + '</div>'
                 + '<div>'
                 + '<label class="block text-xs font-medium text-gray-500 mb-1"><?php _e('字段值'); ?></label>'
                 + '<textarea name="fieldValues[]" class="w-full px-2 py-1.5 bg-white border border-gray-300 text-sm focus:outline-none focus:border-discord-accent transition-colors font-mono" rows="2" placeholder="<?php _e('字段值'); ?>"></textarea>'
                 + '</div>'
                 + '<button type="button" class="btn-delete absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors" title="<?php _e('删除'); ?>"><i class="fas fa-trash-alt"></i></button>'
                 + '</div>',
            el = $(html).hide().appendTo('#custom-field .fields').fadeIn();

        attachDeleteEvent(el);
    });
});
</script>
