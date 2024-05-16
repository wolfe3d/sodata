    $('#addTo :input,select').each(function() {
        $(this).change(function(){
                fieldUpdate(myID,'award',this.id,this.value,this.id,this.id);
        });
    });