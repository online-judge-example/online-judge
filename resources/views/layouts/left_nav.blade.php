<div class="list-group mt-5 text-left pb-5">
@if(auth()->user()->user_type == 1)
    <a href="{{url('setter/')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-home"></i> Home</a>
    <a href="{{url('setter/create')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-plus-square"></i> Create Problem</a>
    <a href="{{url('setter/problems')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-superscript"></i> Problems</a>
    <a href="{{url('setter/category')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-folder"></i> Category</a>
    <a href="{{url('setter/users')}}" class="d-block text-white rounded p-1 mt-1 pl-2 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-users"></i> Users</a>
    <a href="{{url('setter/submissions')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-list"></i> Submissions</a>
    <a href="{{url('setter/images')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-image"></i> Image</a>
    <a href="{{route('setter.debug')}}" class="d-block text-white rounded p-1 pl-2 mt-1 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-bug"></i> Debug</a>
@endif

@if(auth()->user()->user_type == 2)
    <a href="{{url('manager/home')}}" class="d-block text-white rounded p-1 mt-1 pl-2 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-users"></i> Users</a>
@endif
@if(auth()->user()->user_type == 3)
    <a href="{{url('admin/home')}}" class="d-block text-white rounded p-1 mt-1 pl-2 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-users"></i> Users</a>
    <a href="{{url('admin/coordinators')}}" class="d-block text-white rounded p-1 mt-1 pl-2 text-decoration-none" style="background: #292d31;"><i class="fa fa-fw fa-users"></i> Coordinators</a>
@endif

</div>
