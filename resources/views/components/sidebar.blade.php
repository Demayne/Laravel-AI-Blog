<!-- Sidebar with all categories and tags -->
<div class="sidebar">
  <div class="sidebar-section">
    <h3>Categories</h3>
    <ul>
      @foreach($allCategories as $cat)
        <li><a href="{{ url('/category/' . Str::slug($cat->name)) }}">{{ $cat->name }}</a></li>
      @endforeach
    </ul>
  </div>

  <div class="sidebar-section">
    <h3>Tags</h3>
    <ul>
      @foreach($allTags as $tag)
        <li><a href="{{ url('/tag/' . Str::slug($tag->description)) }}">{{ $tag->description }}</a></li>
      @endforeach
    </ul>
  </div>
</div>
