// Basic helpers and validation
function confirmDelete(){ return confirm('Are you sure you want to delete this item?'); }

document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('form').forEach(function(f){
        f.addEventListener('submit', function(e){
            if(f.hasAttribute('data-skip-validate')) return;
            var required = f.querySelectorAll('[required]');
            for(var i=0;i<required.length;i++){
                if(!required[i].value || required[i].value.trim()===''){
                    alert('Please fill all required fields.');
                    required[i].focus();
                    e.preventDefault();
                    return;
                }
            }
        });
    });
});