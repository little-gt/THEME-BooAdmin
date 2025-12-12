<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<script src="<?php $options->adminStaticUrl('js', 'purify.js'); ?>"></script>
<script>
// 封装为全局函数，供 PJAX 重载使用
window.initTableBehavior = function() {
    // 1. 表格全选与行选中逻辑
    $('.typecho-list-table').tableSelectable({
        checkEl     :   'input[type=checkbox]',
        rowEl       :   'tr',
        selectAllEl :   '.typecho-table-select-all',
        actionEl    :   '.dropdown-menu a, button.btn-operate'
    });

    // 2. 下拉菜单逻辑 (Typecho 原生)
    $('.btn-drop').dropdownMenu({
        btnEl       :   '.dropdown-toggle',
        menuEl      :   '.dropdown-menu'
    });

    // 3. 补充：新版 UI 的复选框高亮逻辑 (Bootstrap 风格)
    const checkboxes = document.querySelectorAll('input.typecho-table-select-all, input[name="cid[]"], input[name="uid[]"], input[name="mid[]"]');
    checkboxes.forEach(chk => {
        // 移除旧的监听器防止重复
        const newChk = chk.cloneNode(true);
        if(chk.parentNode) chk.parentNode.replaceChild(newChk, chk);

        newChk.addEventListener('change', function() {
            const row = this.closest('tr');
            if(row) {
                if(this.checked) row.classList.add('table-active');
                else row.classList.remove('table-active');
            }
        });
    });

    // 4. 初始化 Bootstrap Tooltips (如果有)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
};

(function () {
    $(document).ready(function () {
        window.initTableBehavior();
    });
})();
</script>