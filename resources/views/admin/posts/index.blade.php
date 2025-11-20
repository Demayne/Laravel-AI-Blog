<!-- resources/views/admin/posts/index.blade.php -->

@extends('layouts.layout')

@section('title', 'Admin: All Blog Posts')

@section('content')
  <h1 class="text-center">Admin: Blog Posts Overview</h1>

  {{-- Success/Error Messages (from redirects in controller) --}}
  @if (session('success'))
    <div class="alert success-message text-center">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="alert error-message text-center">
      <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    </div>
  @endif

  <div class="admin-actions" style="text-align: center; margin-bottom: 2rem;">
    <a href="{{ route('posts.create') }}" class="button primary-button"><i class="fas fa-plus-circle"></i> Create New Post</a>
  </div>

  @if ($posts->isEmpty())
    <p class="text-center">No blog posts found. <a href="{{ route('posts.create') }}">Create one now!</a></p>
  @else
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Tags</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($posts as $post)
            <tr>
              <td>{{ $post->id }}</td>
              <td><a href="{{ url('/blog/' . $post->id) }}" target="_blank">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a></td>
              <td>{{ optional($post->category)->name ?? 'N/A' }}</td>
              <td>
                @forelse ($post->tags as $tag)
                  <span class="tag-badge">{{ $tag->description }}</span>@if (!$loop->last),@endif
                @empty
                  None
                @endforelse
              </td>
              <td>{{ $post->created_at ? $post->created_at->format('Y-m-d') : 'N/A' }}</td>
              <td class="actions-cell">
                <a href="{{ route('posts.edit', $post->id) }}" class="button small-button edit-button" title="Edit"><i class="fas fa-edit"></i></a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="button small-button delete-button" onclick="return confirm('Are you sure you want to delete this post?');" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <p class="text-center" style="margin-top: 3rem;"><a href="{{ url('/') }}" class="back-link">← Back to Homepage</a></p>
@endsection

{{-- Add some basic admin table styling to your resources/sass/blog.scss --}}
<style>
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
        background-color: #2a2a2a; /* Darker background for table */
        border-radius: 8px;
        overflow: hidden; /* Ensures rounded corners on content */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .admin-table th, .admin-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #3a3a3a; /* Darker border for rows */
        color: #f1f1f1;
    }

    .admin-table th {
        background-color: #383838; /* Header background */
        font-weight: 700;
        color: #64ffda; /* Primary color for headers */
        text-transform: uppercase;
        font-size: 0.9em;
    }

    .admin-table tbody tr:hover {
        background-color: #2f2f2f; /* Slight hover effect */
    }

    .admin-table .tag-badge {
        display: inline-block;
        background-color: #4a4a4a;
        color: #bb86fc; /* Secondary color for tags */
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8em;
        margin-right: 5px;
        margin-bottom: 5px;
        white-space: nowrap;
    }

    .actions-cell a, .actions-cell button {
        display: inline-flex; /* Align icon and text if present */
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9em;
        margin-right: 8px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .edit-button {
        background-color: #6200ee; /* A vibrant purple */
        color: white;
    }
    .edit-button:hover {
        background-color: #3700b3;
        transform: translateY(-2px);
    }

    .delete-button {
        background-color: #cf6679; /* A muted red */
        color: white;
    }
    .delete-button:hover {
        background-color: #b00020;
        transform: translateY(-2px);
    }

    .primary-button {
        background-color: #64ffda;
        color: #121212;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .primary-button:hover {
        background-color: lighten(#64ffda, 5%);
        transform: translateY(-2px);
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }

    .success-message {
        background-color: #4CAF50; /* Green */
        color: white;
    }

    .error-message {
        background-color: #f44336; /* Red */
        color: white;
    }

    /* Responsive Table */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto; /* Adds horizontal scroll if table is too wide */
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        }

        .admin-table {
            display: block;
            width: 100%;
        }

        .admin-table thead {
            display: none; /* Hide header on small screens */
        }

        .admin-table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #3a3a3a;
            border-radius: 8px;
        }

        .admin-table td {
            display: block;
            text-align: right;
            padding-left: 50%; /* Make space for pseudo-element label */
            position: relative;
            border: none;
        }

        .admin-table td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: calc(50% - 30px);
            text-align: left;
            font-weight: bold;
            color: #bb86fc; /* Secondary color for labels */
        }

        /* Add data-label attributes to your <td> elements */
        .admin-table td:nth-of-type(1)::before { content: "ID"; }
        .admin-table td:nth-of-type(2)::before { content: "Title"; }
        .admin-table td:nth-of-type(3)::before { content: "Category"; }
        .admin-table td:nth-of-type(4)::before { content: "Tags"; }
        .admin-table td:nth-of-type(5)::before { content: "Created At"; }
        .admin-table td:nth-of-type(6)::before { content: "Actions"; }
    }
</style>