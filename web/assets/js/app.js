(function($) {

    var initialForm = {
        
        init: function() {
            
            console.log('FileChangeLog initialized');
            
            $('.input-group.date').datepicker({
                format: 'yyyymmdd',
                language: 'pl'
            });
            
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
    
    var columnChecker = {
        
        context: null,
        
        init: function() {
            
            this.context = $('.grid-wrapper');
            
            console.log('Column checker initialized');
            
            $('button[type=submit]', this.context).prop('disabled', true);
            
            $('.grid-wrapper').on('change', 'select', $.proxy(this.changeEvent, this));
            
            $('.grid-wrapper select').trigger('change');
            
            this.showMissingFields($('select:eq(0) option[value!=""]', this.context));
            
        },
        
        showMissingFields: function(remaining)
        {
            var container = $('#missing-fields');
            
            var list = $('<ul>', {
                class: "list-inline"
            });
            
            remaining.each(function() {
                
                var label = '<span class="label label-danger">%s</span>'.replace('%s', $(this).text());
                var li = $('<li>').html(label);
                
                list.append(li);
                
            });
            
            list.prepend("Pola do uzupełnienia:<br />");
            
            container.html(list);
        },
        
        changeEvent: function(e)
        {   
            $ct = $(e.currentTarget);
            $ctOption = $('select option[value="' + $ct.val() + '"]',this.context);
            
            if($ct.val())
            {
                $ct.closest('th').addClass('correct');
                $ctOption.html($ctOption.eq(0).text() + ' &#10004;')
            }
            else 
            {
                $ct.closest('th').removeClass('correct');
                $('option', $ct).each(function() {
                    $(this).html($(this).text().replace(' &#10004;', ''))
                });
                        
                        
            }
            
            var context = this.context;
            
            var remaining = $('select:eq(0) option[value!=""]', context).filter(function() {
                
                if($('select option[value="' + $(this).val() +'"]:selected', context).length)
                {
                    return false;
                }
                
                return true;
                
            });
            
            this.showMissingFields(remaining);
            
            if(remaining.length === 0)
            {
                $('button[type=submit]').prop('disabled', false).on('click', function(e) {
                    
                    $('#download').modal({show: true}).on('hidden.bs.modal', function (e) {
                    
                        window.location.href = $('form').data('redirect');
                    
                    });
                    
                    
                    
                });
            }
            else {
                $('button[type=submit]').prop('disabled', true);
            }
        }
        
    }
    
    $(function() {
        initialForm.init();
        columnSwitcher.init();
        columnChecker.init();
    });
    
})(jQuery);