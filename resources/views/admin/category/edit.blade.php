@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('categories.update',['category' => $category->id]) }}" method="post" enctype="multipart/form-data" id="categoryForm"
                name="categoryForm">
                @method('PUT')
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}"
                                        placeholder="Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug" id="slug" class="form-control" value="{{ $category->slug }}"
                                        placeholder="Slug">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="hidden" name="image_id" id="image_id" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop file here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($category->image))
                                <div>
                                    <img src="{{ asset('uploads/category/thumb/'.$category->image) }}" width="100" alt="">
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ ($category->status==1) ?'selected':'' }} value="1">Active</option>
                                        <option {{ ($category->status==0) ?'selected':'' }} value="0">Inactive</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Show On Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option {{ ($category->showHome=='Yes') ?'selected':'' }} value="Yes">Yes</option>
                                        <option {{ ($category->showHome=='No') ?'selected':'' }} value="No">No</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        $(document).ready(function() {
            $("#categoryForm").submit(function(event) {
                event.preventDefault();
                var element = $(this);
                $.ajax({
                    url: "{{ route('categories.update',$category->id) }}",
                    type: "put",
                    data: element.serialize(),
                    datatype: 'json',
                    success: function(response) {
                        if (response.status) {
                            // Store the flash message in sessionStorage
                            sessionStorage.setItem('flashMessage', response.message);
                            window.location.href = response.redirect;
                        } else {
                            var errors = response.errors;
                            if (errors.name) {
                                $('#name').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.name);
                            } else {
                                $('#name').removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("");
                            }
                            if (errors.slug) {
                                $('#slug').addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.slug);
                            } else {
                                $('#slug').removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("");
                            }
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log("something went wrong");
                    }
                });
            });

            $("#name").change(function() {
                var element = $(this);
                $.ajax({
                    url: "{{ route('getSlug') }}",
                    type: 'get',
                    data: {
                        title: element.val()
                    },
                    datatype: 'json',
                    success: function(response) {
                        if (response.status) {
                            $("#slug").val(response.slug);
                        }
                    }
                });
            });
        });

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                 $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection
