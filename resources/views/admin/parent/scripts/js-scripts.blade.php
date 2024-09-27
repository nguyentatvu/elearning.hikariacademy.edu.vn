<script src="{{JS}}angular.js"></script>
<script src="{{JS}}angular-messages.js"></script>
  <script src="{{JS}}select2.js"></script>
  <link rel="stylesheet" type="text/css" href="{{CSS}}select2.css">
<script >
  var app = angular.module('academia',  ['ngMessages']);
  app.controller('angTopicsController', function($scope, $http) {
});
 
      /**
      * Intilize select by default
      */
    $('.select2').select2({
       placeholder: "Select",
    });

    function getSubjectParents()
    {
      subject_id = $('#subject').val();
      route = '/parent/ajaxn/'+subject_id;  
      var token = $('[name="_token"]').val();
      data= {_method: 'get', '_token':token, 'subject_id': subject_id};
      
      $.ajax({
          url:route,
          dataType: 'json',
          data: data,
          success:function(result){
              $('#parent_n').empty();
              for(i=0; i<result.length; i++) {
               $('#parent_n').append('<option value="'+result[i].id+'">'+result[i].text+'</option>');
              }
            }
      });
    }
 

</script>

