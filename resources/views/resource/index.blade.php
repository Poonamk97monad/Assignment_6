@extends('parent')

@section('main')
    @if ($strMessage =Session::get('success') )
        <div class="alert alert-success">
            <p>{{$strMessage}}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Resource</title>
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css"/>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    </head>
    <h5 class="right">
        <form action="/search" method="get">
            <input type="text" name="search">
            <input type="submit" value="Search">
        </form>
    </h5>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#resourceModal">Add New Resource</button>
    <hr>
    <h2>Resources</h2>
    <hr>
    <table class="table table-bordered table-striped" id="resource_table">
        <tr>
            <th width="35%">Files</th>
            <th width="35%">Title</th>
            <th width="35%">Slug</th>
            <th width="35%">Description</th>
            <th width="30%">Action</th>
            <th width="30%">Favorites</th>
        </tr>
        @foreach($arrObjResource as $objResource)
            <tr>
                <td><img src="{{ URL::to('/') }}/file_upload/{{ $objResource->file_upload }}" class="img-thumbnail" width="75"  /></td>
                <td>{{ $objResource->title }}</td>
                <td>{{ $objResource->slug }}</td>
                <td>{{ $objResource->description }}</td>
                <td>
                    <a href="{{route('resources.show',$objResource->id)}}" class="btn btn-primary">view</a>
                </td>
                <td class="resource_{{$objResource->getKey()}}">
                    @if($objResource->isFavortted() == 1)
                        <button class="btn btn-success btn-favorites" data-id="{{$objResource->getKey()}}" data-value="true">UnFavorites</button>
                    @else
                        <button class="btn btn-success btn-favorites" data-id="{{$objResource->getKey()}}" data-value="false">Favorites</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    <hr>
    {!! $arrObjResource->links() !!}

    <div class="modal fade" id="resourceModal" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="favoritesModalLabel">Resources list</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <form method="post" id="upload_form" enctype="multipart/form-data" >
                            {{ csrf_field() }}
                            <span id="form_output"></span>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Enter Title of File</label>
                                <div class="col-md-8">
                                    <input type="text" name="title" id="title" class="form-control input-lg" />
                                </div>
                            </div>
                            <br/><br/><br/>
                            <div class="form-group">
                                <label class="col-md-4 text-right">Enter Description</label>
                                <div class="col-md-8">
                                    <input type="text" name="description" id="description" class="form-control input-lg" />
                                </div>
                            </div>
                            <br />
                            <br />
                            <br />
                            <div class="form-group">
                                <label class="col-md-4 text-right">Select file upload</label>
                                <div class="col-md-8">
                                    <input type="file" name="file_upload" id="file_upload"/>
                                </div>
                            </div>
                            <br /><br /><br />
                            <div class="form-group text-center">
                                <button class="btn btn-primary btn-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    $("#upload_form").submit(function(e){
        e.preventDefault();
        $('#resourceModal').modal('toggle');
        $.ajax({
            url:'/resources/post',
            method:"POST",
            data:new FormData(this),
            dataType:'JSON',
            contentType: false,
            cache: false,
            processData: false,

            success:function(data){
               console.log(data);
                 alert(data.success);
                window.location="/resources";

            }
        });
    });
        $(".btn-favorites").click(function(e){

            e.preventDefault();

            var boolIsFavoritted = $(this).data('value');
            var intResourceId = $(this).data('id');

            $.ajax({

                type:'POST',

                url:'/add_favorites_resource/' + intResourceId,

                success:function(data){

                    console.log(boolIsFavoritted)
                    if(boolIsFavoritted) {
                        $('.resource_'+intResourceId+' button').html('Favorites').data('value', false);
                    }
                    else {
                        $('.resource_'+intResourceId+' button').html('UnFavorites').data('value', true);
                    }
                }
            });
        });
    </script>
@endsection
