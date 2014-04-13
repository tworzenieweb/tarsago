(function($) {

    var fileChangeLog = {
        
        init: function() {
            
            console.log('FileChangeLog initialized');
            
            $(document).on('change', '.btn-file :file', function() {
                
                console.log('file changed');
                
                var input = $(this),
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });
            
            $('.btn-file :file').on('fileselect', $.proxy(this.fileSelectEvent, this));
            
        },
        
        fileSelectEvent: function(event, numFiles, label) {

            var input = $(event.currentTarget).parents('.input-group').find(':text'),
                log = label;

            if (input.length) {
                input.val(log);
            } else {
                if (log)
                {
                    window.alert(log);
                }
            }

        }
        
    };
    
    var columnSwitcher = {
        
        init: function() {
            
            console.log('column hidder initialized');
            
            $('.grid-wrapper').on('click', '.hide-column', $.proxy(this.hideColumnEvent, this));
            
        },
        hideColumnEvent: function(e) {
            e.preventDefault();
            
            var target = $(e.currentTarget);
            
            if(!target.val())
            {
                var columnNumber = target.closest('th').index()+1;
                
                console.log('Column ' + columnNumber + ' to hide');
                
                $('td:nth-child(' + columnNumber + '), th:nth-child(' + columnNumber + ')').fadeOut();
                
            }
        }
        
    }
    
    $(function() {
        fileChangeLog.init();
        columnSwitcher.init();
    });
    
})(jQuery);