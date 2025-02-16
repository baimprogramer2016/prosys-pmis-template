  <!-- Sidebar -->
  <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="#" class="logo">
          <img
            src="{{asset('assets/img/logo-light.png')}}"
            alt="navbar brand"
            class="navbar-brand"
            height="40"
          />
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
          </button>
          <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
          </button>
        </div>
        <button class="topbar-toggler more">
          <i class="gg-more-vertical-alt"></i>
        </button>
      </div>
      <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          

          <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('gantt-chart*') ? 'active' : '' }}">
            <a href="#">
             
              <p>Menu</p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('surat*') ? 'active' : '' }}">
            <a href="{{ route('surat') }}">
              <i class="fas fa-pen-square"></i>
              <p>Letter</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#schedule">
              <i class="fas fa-calendar-alt"></i>
              <p>Schedule Management</p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('schedule-management*') || Request::is('s-curve*') ? 'show' : '' }}"" id="schedule">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('schedule-management') ? 'active' : '' }}">
                  <a href="{{ route('schedule-management') }}">
                    <span class="sub-item">Schedule</span>
                  </a>
                </li>
                <li class="{{ Request::is('s-curve') ? 'active' : '' }}">
                  <a href="{{ route('s-curve') }}">
                    <span class="sub-item">Input S-Curve</span>
                  </a>
                </li>
               
                <li class="{{ Request::is('s-curve-chart') ? 'active' : '' }}">
                  <a href="{{ route('s-curve-chart') }}">
                    <span class="sub-item">S-Curve</span>
                  </a>
                </li>               
                <li class="{{ Request::is('s-curve-bar') ? 'active' : '' }}">
                  <a href="{{ route('s-curve-bar') }}">
                    <span class="sub-item">Progress</span>
                  </a>
                </li>  
              </ul>
            </div>
          </li>
          {{-- <li class="nav-item {{ Request::is('master-schedule*') ? 'active' : '' }}">
            <a href="{{ route('master-schedule') }}">
              <i class="fas fa-calendar-alt"></i>
              <p>Schedule Management</p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('gantt-chart*') ? 'active' : '' }}">
            <a href="{{ route('gantt-chart') }}">
              <i class="fas fa-car-side"></i>
              <p>Task Progress</p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="#">
             
              <p>Document Management</p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('sop*') ? 'active' : '' }}">
            <a href="{{ route('sop') }}">
              <i class="fas fa-pen-square"></i>
              <p>Project Procedure (SOP)</p>
            
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#engineering">
              <i class="fas fa-tachometer-alt"></i>

              <p>Engineering</p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('document-engineer*') || Request::is('document-engineer') ? 'show' : '' }}"" id="engineering">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('document-engineer-tambah') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-tambah') }}">
                    <span class="sub-item">Upload Document</span>
                  </a>
                </li>
                <li class="{{ Request::is('document-engineer-check') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-check') }}">
                    <span class="sub-item">Check</span>
                  </a>
                </li>
                <li class="{{ Request::is('document-engineer-review') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-review') }}">
                    <span class="sub-item">Review</span>
                  </a>
                </li>
                <li class="{{ Request::is('document-engineer-approve') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-approve') }}">
                    <span class="sub-item">Approve</span>
                  </a>
                </li>
                <li class="{{ Request::is('document-engineer-master-deliverables-register') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-master-deliverables-register') }}">
                    <span class="sub-item">MDR</span>
                  </a>
                </li>
                <li class="{{ Request::is('document-engineer-basic-design') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-basic-design') }}">
                    <span class="sub-item">Basic Design</span>
                  </a>
                </li>   
                <li class="{{ Request::is('document-engineer-detail-engineering-design') ? 'active' : '' }}">
                  <a href="{{ route('document-engineer-detail-engineering-design') }}">
                    <span class="sub-item">DED</span>
                  </a>
                </li>                  
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#constructiondocument">
              <i class="fas fa-hotel"></i>

              <p>Construction Document</p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('construction-document*') || Request::is('construction-document') ? 'show' : '' }}"" id="constructiondocument">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('construction-document-tambah') ? 'active' : '' }}">
                  <a href="{{ route('construction-document-tambah') }}">
                    <span class="sub-item">Upload Document</span>
                  </a>
                </li>
                <li class="{{ Request::is('construction-document-check') ? 'active' : '' }}">
                  <a href="{{ route('construction-document-check') }}">
                    <span class="sub-item">Check</span>
                  </a>
                </li>
                <li class="{{ Request::is('construction-document-review') ? 'active' : '' }}">
                  <a href="{{ route('construction-document-review') }}">
                    <span class="sub-item">Review</span>
                  </a>
                </li>
                <li class="{{ Request::is('construction-document-approve') ? 'active' : '' }}">
                  <a href="{{ route('construction-document-approve') }}">
                    <span class="sub-item">Approve</span>
                  </a>
                </li>
                <li class="{{ Request::is('construction-document') ? 'active' : '' }}">
                  <a href="{{ route('construction-document') }}">
                    <span class="sub-item">Construction Document</span>
                  </a>
                </li>
                            
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#fieldinstruction">
              <i class="fas fas fa-chess-rook"></i>

              <p>Field Instructions</p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('field-instruction*') || Request::is('field-instruction') ? 'show' : '' }}"" id="fieldinstruction">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('field-instruction-tambah') ? 'active' : '' }}">
                  <a href="{{ route('field-instruction-tambah') }}">
                    <span class="sub-item">Upload Document</span>
                  </a>
                </li>
                <li class="{{ Request::is('field-instruction-check') ? 'active' : '' }}">
                  <a href="{{ route('field-instruction-check') }}">
                    <span class="sub-item">Check</span>
                  </a>
                </li>
                <li class="{{ Request::is('field-instruction-review') ? 'active' : '' }}">
                  <a href="{{ route('field-instruction-review') }}">
                    <span class="sub-item">Review</span>
                  </a>
                </li>
                <li class="{{ Request::is('field-instruction-approve') ? 'active' : '' }}">
                  <a href="{{ route('field-instruction-approve') }}">
                    <span class="sub-item">Approve</span>
                  </a>
                </li>
                <li class="{{ Request::is('field-instruction') ? 'active' : '' }}">
                  <a href="{{ route('field-instruction') }}">
                    <span class="sub-item">Field Instructions</span>
                  </a>
                </li>
                         
              </ul>
            </div>
          </li>
          {{-- <li class="nav-item">
            <a data-bs-toggle="collapse" href="#construction">
              <i class="fas fa-hotel"></i>

              <p>Construction</p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('document') || Request::is('gantt-chart') ? '' : '' }}"" id="construction">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('document') ? 'active' : '' }}">
                  <a href="{{ route('document') }}">
                    <span class="sub-item">Construction Document</span>
                  </a>
                </li>
                <li class="{{ Request::is('gantt-chart') ? 'active' : '' }}">
                  <a href="{{ route('gantt-chart') }}">
                    <span class="sub-item">Field Instructions</span>
                  </a>
                </li>               
              </ul>
            </div>
          </li> --}}
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#correspondence">
              <i class="fas fa-folder-open"></i>

              <p>Correspondence </p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('surat-masuk*') || Request::is('surat-keluar*') ? 'show' : '' }}"" id="correspondence">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('surat-masuk*') ? 'active' : '' }}">
                  <a href="{{ route('surat-masuk') }}">
                    <span class="sub-item">Surat Masuk</span>
                  </a>
                </li>
                <li class="{{ Request::is('surat-keluar*') ? 'active' : '' }}">
                  <a href="{{ route('surat-keluar') }}">
                    <span class="sub-item">Surat Keluar</span>
                  </a>
                </li>               
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a href="#">
              <p>Report</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#report">
              <i class="fas fa-folder"></i>

              <p>Report</p>
              <span class="caret"></span>
            </a>
            <div class="collapse {{ Request::is('report*') || Request::is('report') ? 'show' : '' }}"" id="report">
              <ul class="nav nav-collapse">
                <li class="{{ Request::is('report-daily*') ? 'active' : '' }}">
                  <a href="{{ route('report-daily') }}">
                    <span class="sub-item">Daily Report</span>
                  </a>
                </li>
                <li class="{{ Request::is('report-weekly') ? 'active' : '' }}">
                  <a href="{{ route('report-weekly') }}">
                    <span class="sub-item">Weekly Report</span>
                  </a>
                </li>  
                <li class="{{ Request::is('report-monthly*') ? 'active' : '' }}">
                  <a href="{{ route('report-monthly') }}">
                    <span class="sub-item">Monthly Report</span>
                  </a>
                </li>               
              </ul>
            </div>
          </li>
          <li class="nav-item {{ Request::is('mom*') ? 'active' : '' }}">
            <a href="{{ route('mom') }}">
              <i class="fas fa-pen"></i>
              <p>Minutes Of Meeting</p>
            
            </a>
          </li>
          {{-- <li class="nav-item {{ Request::is('file-manager*') ? 'active' : '' }}">
            <a href="{{ route('file-manager') }}">
              <i class="fas fa-folder"></i>
              <p>File Manager</p>
            </a>
          </li> --}}
          <li class="nav-item {{ Request::is('gantt-chart*') ? 'active' : '' }}">
            <a href="#">
             
              <p>Role & Permission</p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
            <a href="{{ route('users') }}">
              <i class="fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('document*') ? 'active' : '' }}">
            <a href="{{ route('gantt-chart') }}">
              <i class="fas fa-key"></i>
              <p>Role</p>
            </a>
          </li>
      
          {{-- <li class="nav-item">
            <a data-bs-toggle="collapse" href="#dokument">
              <i class="fas fa-folder"></i>

              <p>Document</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="dokument">
              <ul class="nav nav-collapse">
                <li>
                  <a href="components/avatars.html">
                    <span class="sub-item">Folder</span>
                  </a>
                </li>
                             
              </ul>
            </div>
          </li> --}}
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->