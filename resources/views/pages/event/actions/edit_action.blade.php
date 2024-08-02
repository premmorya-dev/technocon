  <!--begin::Action-->
  <a href="#" class="btn btn-primary btn-sm dropdown-toggles" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
      Actions
      <i class="ki-duotone ki-down fs-5 ms-1"></i>
  </a>
  <!--begin::Menu-->
  <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">



      <!--begin::Menu item-->
      <div class="menu-item px-3">
          <a href="{{ route('event.edit',['event_id'=>$event->event_id]) }}" event-id="{{ $event->event_id }}" class="menu-link px-3 btn-sm "> <i class=" fa-solid fa-eye text-primary "></i>&nbsp; Edit</a>
      </div>
      <!--end::Menu item-->

      <!--begin::Menu item-->
      <div class="menu-item px-3">
          <a href="javascript:void(0);" event-id="{{ $event->event_id }}" class="menu-link px-3 btn-sm single-delete"> <i class="fa-solid fa-trash text-danger"></i>&nbsp; Delete</a>
      </div>
      <!--end::Menu item-->

  </div>
  <!--end::Menu-->

  <!--end::Action-->