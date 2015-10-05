(function($){
    $(document).ready(function() {
        var LevelMetabox = function(){
            this.form = $('#post');
            this.btnRemoveRule = $('.btnRemoveRule');
            this.btnAddRule = $('.btnAddRule');
            this.count = this.btnAddRule.data('count');
        };
        LevelMetabox.prototype.onRemoveRule = function(e){
            e.preventDefault();
            var target  =$( e.currentTarget);
            var group_id = target.data('id');
            this.count--;
            $('#' + group_id ).remove();
        };
        LevelMetabox.prototype.onAddeRule = function(e){
            e.preventDefault();
            console.log(this);
            var target  =$( e.currentTarget);
            var groupHtml = $('<div id="group_'+this.count+'"><br><label>Label <input name="rules['+this.count+'][label]" value="" type="text"></label><br><label>Rule <input name="rules['+this.count+'][rule]" value="" type="text"></label><br><label>Arguments <input name="rules['+this.count+'][argument]" value="" type="text"></label> <button class="btnRemoveRule" data-id="group_'+this.count+'">Remove</button></div>');
            this.count++;
            groupHtml.insertBefore(target);
        };
        LevelMetabox.prototype.init = function(){
            var self = this;
            this.btnRemoveRule.on('click', function(e){
                self.onRemoveRule(e);
            });
            this.btnAddRule.on('click', function(e){
                self.onAddeRule(e);
            });
        };

        /**
         * @type {LevelMetabox} the id selector of post form
         */
        var levelMetabox = new LevelMetabox();
        levelMetabox.init();
    });
})(jQuery);