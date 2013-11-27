<script type="text/javascript">
function addEvent(element, type, listener) {
    if (element.addEventListener) {
        element.addEventListener(type, listener, false);
        return true;
    }
    else if (element.attachEvent) {
        return element.attachEvent("on" + type, listener);
    }
    return false;
}

function WikiEditInit() {
    $(document).ready(function(){
        TracWysiwyg.tracPaths = { base: ".", stylesheets: [] };
        $(".wikiEdit").each(function(){
            var id = $(this).attr('id');
            var init    = $(this).hasClass('wikiNo');
            if (false == init) {
                new TracWysiwyg(this);
            }

        });
    });
}

WikiEditInit();
</script>