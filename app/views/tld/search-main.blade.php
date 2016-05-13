{{ HTML::style('https://bootswatch.com/flatly/bootstrap.min.css') }}


{{ Form::open(array('url' => '#', 'class'=> 'form')) }}

{{ Form::text('TLD', 'tld', array('class' => 'tld-input form-control')) }}

<div id="table" class="table-class">
<table class="table table-striped table-hover ">
  <thead>
    <tr>Result</tr>
</thead>
<tbody>
</tbody>
</table>
</div>

<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

{{ HTML::script('js/app.js') }}

