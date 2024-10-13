<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="container" style="margin-top: 50px">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">LARAVEL CRUD AJAX</h4>
                    <div class="card border-0 shadow-sm rounded-md mt-4">
                        <div class="card-body">

                            <a href="javascript:void(0)" class="btn btn-success mb-2" id="btn-create-post">TAMBAH</a>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Authors</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="table-posts">
                                    @foreach ($posts as $post)
                                        <tr id="index_{{ $post->id }}">
                                            <td>{{ $post->user->name }}</td>
                                            <td>{{ $post->title }}</td>
                                            <td>{{ $post->content }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" id="btn-edit-post"
                                                    data-id="{{ $post->id }}"
                                                    class="btn btn-primary btn-sm">EDIT</a>
                                                <a href="javascript:void(0)" id="btn-delete-post"
                                                    data-id="{{ $post->id }}"
                                                    class="btn btn-danger btn-sm">DELETE</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modal-create')
        @include('modal-edit')
        @include('delete-post')
            </div>
        </div>
    </div>
</x-app-layout>