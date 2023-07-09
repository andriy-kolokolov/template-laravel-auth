@extends('admin.layouts.base')

@section('contents')

    <h1>Posts</h1>

    @if (session('delete_success'))
        @php $project = session('delete_success') @endphp
        <div class="alert alert-danger">
            Post "{{ $project->title }}" was deleted.
            {{-- <form
                action="{{ route("admin.posts.restore", ['post' => $post]) }}"
                    method="post"
                    class="d-inline-block"
                >
                @csrf
                <button class="btn btn-warning">Ripristina</button>
            </form> --}}
        </div>
    @endif

    {{-- @if (session('restore_success'))
        @php $post = session('restore_success') @endphp
        <div class="alert alert-success">
            La post "{{ $post->title }}" è stata ripristinata
        </div>
    @endif --}}

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Title</th>
            <th>Programming Languages</th>
            <th>Frameworks</th>
            <th>Description</th>
            <th>Project URL</th>
        </tr>
        </thead>
        <tbody>
        @foreach($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>
                    @foreach($project->languages as $language)
                        {{ $language->programming_language }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($project->frameworks as $framework)
                        {{ $framework->framework }}<br>
                    @endforeach
                </td>
                <td>{{ $project->description }}</td>
                <td><a href="{{ $project->project_url }}" target="_blank">{{ $project->project_url }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <form
                        action=""
{{--                        data-template="{{ route('admin.posts.destroy', ['post' => '*****']) }}"--}}
                        method="post"
                        class="d-inline-block"
                        id="confirm-delete"
                    >
                        @csrf
                        @method('delete')
                        <button class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{ $projects->links() }}

@endsection
