@extends('layouts.app')

@section('title', 'UI Components')
@section('page-title', 'UI Components')

@section('content')
    <!-- Buttons Section -->
    <!-- Buttons Section -->
    <div class="component-card">
        <h4>Button Variants</h4>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Solid Buttons</h6>
                <button class="btn-custom btn-primary-custom me-2 mb-2">Primary</button>
                <button class="btn-custom btn-secondary-custom me-2 mb-2">Secondary</button>
                <button class="btn-custom btn-success-custom me-2 mb-2">Success</button>
                <button class="btn-custom btn-warning-custom me-2 mb-2">Warning</button>
                <button class="btn-custom btn-danger-custom me-2 mb-2">Danger</button>
                <button class="btn-custom btn-info-custom me-2 mb-2">Info</button>
                <button class="btn-custom btn-dark-custom me-2 mb-2">Dark</button>
                <button class="btn-custom btn-light-custom me-2 mb-2">Light</button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Outline Buttons</h6>
                <button class="btn-custom btn-outline-primary-custom me-2 mb-2">Primary</button>
                <button class="btn-custom btn-outline-secondary-custom me-2 mb-2">Secondary</button>
                <button class="btn-custom btn-outline-success-custom me-2 mb-2">Success</button>
                <button class="btn-custom btn-outline-warning-custom me-2 mb-2">Warning</button>
                <button class="btn-custom btn-outline-danger-custom me-2 mb-2">Danger</button>
                <button class="btn-custom btn-outline-info-custom me-2 mb-2">Info</button>
                <button class="btn-custom btn-outline-dark-custom me-2 mb-2">Dark</button>
                <button class="btn-custom btn-outline-light-custom me-2 mb-2">Light</button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Gradient Buttons</h6>
                <button class="btn-custom btn-gradient-primary me-2 mb-2">Primary Gradient</button>
                <button class="btn-custom btn-gradient-success me-2 mb-2">Success Gradient</button>
                <button class="btn-custom btn-gradient-warning me-2 mb-2">Warning Gradient</button>
                <button class="btn-custom btn-gradient-danger me-2 mb-2">Danger Gradient</button>
                <button class="btn-custom btn-gradient-purple me-2 mb-2">Purple Gradient</button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Rounded Buttons</h6>
                <button class="btn-custom btn-primary-custom btn-rounded me-2 mb-2">Rounded Primary</button>
                <button class="btn-custom btn-success-custom btn-rounded me-2 mb-2">Rounded Success</button>
                <button class="btn-custom btn-outline-primary-custom btn-rounded me-2 mb-2">Rounded Outline</button>
                <button class="btn-custom btn-gradient-primary btn-rounded me-2 mb-2">Rounded Gradient</button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Button Sizes</h6>
                <button class="btn-custom btn-primary-custom btn-xs me-2 mb-2">Extra Small</button>
                <button class="btn-custom btn-primary-custom btn-sm me-2 mb-2">Small</button>
                <button class="btn-custom btn-primary-custom me-2 mb-2">Regular</button>
                <button class="btn-custom btn-primary-custom btn-lg me-2 mb-2">Large</button>
                <button class="btn-custom btn-primary-custom btn-xl me-2 mb-2">Extra Large</button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Icon Buttons</h6>
                <button class="btn-custom btn-primary-custom me-2 mb-2">
                    <i class="bi bi-download me-2"></i>Download
                </button>
                <button class="btn-custom btn-success-custom me-2 mb-2">
                    <i class="bi bi-check-lg me-2"></i>Accept
                </button>
                <button class="btn-custom btn-danger-custom me-2 mb-2">
                    <i class="bi bi-trash me-2"></i>Delete
                </button>
                <button class="btn-custom btn-outline-primary-custom me-2 mb-2">
                    <i class="bi bi-send me-2"></i>Send Message
                </button>
                <button class="btn-custom btn-outline-success-custom me-2 mb-2">
                    <i class="bi bi-save me-2"></i>Save Changes
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Icon Only Buttons</h6>
                <button class="btn-custom btn-primary-custom btn-icon me-2 mb-2">
                    <i class="bi bi-heart"></i>
                </button>
                <button class="btn-custom btn-success-custom btn-icon me-2 mb-2">
                    <i class="bi bi-star"></i>
                </button>
                <button class="btn-custom btn-warning-custom btn-icon me-2 mb-2">
                    <i class="bi bi-bell"></i>
                </button>
                <button class="btn-custom btn-danger-custom btn-icon me-2 mb-2">
                    <i class="bi bi-trash"></i>
                </button>
                <button class="btn-custom btn-outline-primary-custom btn-icon me-2 mb-2">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn-custom btn-outline-success-custom btn-icon me-2 mb-2">
                    <i class="bi bi-check"></i>
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Social Buttons</h6>
                <button class="btn-custom btn-facebook me-2 mb-2">
                    <i class="bi bi-facebook me-2"></i>Facebook
                </button>
                <button class="btn-custom btn-twitter me-2 mb-2">
                    <i class="bi bi-twitter me-2"></i>Twitter
                </button>
                <button class="btn-custom btn-google me-2 mb-2">
                    <i class="bi bi-google me-2"></i>Google
                </button>
                <button class="btn-custom btn-github me-2 mb-2">
                    <i class="bi bi-github me-2"></i>GitHub
                </button>
                <button class="btn-custom btn-linkedin me-2 mb-2">
                    <i class="bi bi-linkedin me-2"></i>LinkedIn
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">Animated Buttons</h6>
                <button class="btn-custom btn-primary-custom btn-pulse me-2 mb-2">
                    <i class="bi bi-bell me-2"></i>Pulse Button
                </button>
                <button class="btn-custom btn-success-custom btn-shine me-2 mb-2">
                    <i class="bi bi-star me-2"></i>Shine Effect
                </button>
                <button class="btn-custom btn-gradient-primary btn-bounce me-2 mb-2">
                    <i class="bi bi-arrow-down me-2"></i>Bounce on Hover
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12">
                <h6 class="text-muted mb-3">State Buttons</h6>
                <button class="btn-custom btn-primary-custom me-2 mb-2" disabled>Disabled Button</button>
                <button class="btn-custom btn-outline-primary-custom me-2 mb-2" disabled>Disabled Outline</button>
                <button class="btn-custom btn-primary-custom btn-loading me-2 mb-2">
                    <i class="bi bi-arrow-repeat spinner me-2"></i>Loading...
                </button>
                <button class="btn-custom btn-success-custom btn-checked me-2 mb-2">
                    <i class="bi bi-check-lg me-2"></i>Completed
                </button>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <h6 class="text-muted mb-3">Button Groups</h6>
                <div class="btn-group-custom me-3 mb-3">
                    <button class="btn-custom btn-primary-custom">Left</button>
                    <button class="btn-custom btn-primary-custom">Middle</button>
                    <button class="btn-custom btn-primary-custom">Right</button>
                </div>

                <div class="btn-group-vertical-custom me-3 mb-3">
                    <button class="btn-custom btn-outline-primary-custom">Top</button>
                    <button class="btn-custom btn-outline-primary-custom">Middle</button>
                    <button class="btn-custom btn-outline-primary-custom">Bottom</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="component-card">
        <h4>Alerts</h4>
        <div class="alert-custom alert-success-custom">
            <i class="bi bi-check-circle me-2"></i>
            This is a success alert!
        </div>
        <div class="alert-custom alert-warning-custom">
            <i class="bi bi-exclamation-triangle me-2"></i>
            This is a warning alert!
        </div>
        <div class="alert-custom alert-danger-custom">
            <i class="bi bi-x-circle me-2"></i>
            This is a danger alert!
        </div>
        <div class="alert-custom alert-info-custom">
            <i class="bi bi-info-circle me-2"></i>
            This is an info alert!
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="component-card">
        <h4>Progress Bars</h4>
        <div class="mb-3">
            <label class="form-label-custom">Primary Progress (75%)</label>
            <div class="progress-container">
                <div class="progress-bar-custom progress-primary" style="width: 75%"></div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label-custom">Success Progress (90%)</label>
            <div class="progress-container">
                <div class="progress-bar-custom progress-success" style="width: 90%"></div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label-custom">Warning Progress (50%)</label>
            <div class="progress-container">
                <div class="progress-bar-custom progress-warning" style="width: 50%"></div>
            </div>
        </div>
    </div>

    <!-- Form Elements -->
    <div class="component-card">
        <h4>Form Elements</h4>
        <form id="demoForm" data-validate>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom" for="name">Full Name</label>
                        <input type="text" class="form-control-custom" id="name" data-validate="required" placeholder="Enter your name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom" for="email">Email Address</label>
                        <input type="email" class="form-control-custom" id="email" data-validate="email" placeholder="Enter your email">
                    </div>
                </div>
            </div>
            <div class="form-group-custom">
                <label class="form-label-custom" for="message">Message</label>
                <textarea class="form-control-custom" id="message" rows="4" placeholder="Enter your message"></textarea>
            </div>
            <button type="submit" class="btn-custom btn-primary-custom">Submit Form</button>
        </form>
    </div>

    <!-- Tabs -->
    <div class="component-card">
        <h4>Tabs</h4>
        <div class="tabs-custom">
            <button class="tab-btn active" data-tab="tab1">Tab 1</button>
            <button class="tab-btn" data-tab="tab2">Tab 2</button>
            <button class="tab-btn" data-tab="tab3">Tab 3</button>
        </div>
        <div class="tab-content active" id="tab1">
            <p>This is the content for Tab 1. You can put any content here.</p>
        </div>
        <div class="tab-content" id="tab2">
            <p>This is the content for Tab 2. Different content goes here.</p>
        </div>
        <div class="tab-content" id="tab3">
            <p>This is the content for Tab 3. Another set of content here.</p>
        </div>
    </div>

    <!-- Modal Demo -->
    <div class="component-card">
        <h4>Modal</h4>
        <button class="btn-custom btn-primary-custom" onclick="new Modal('demoModal').open()">
            Open Modal
        </button>
    </div>

    <!-- Badges -->
    <div class="component-card">
        <h4>Badges</h4>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge-custom badge-primary-custom">Primary</span>
            <span class="badge-custom badge-success-custom">Success</span>
            <span class="badge-custom badge-warning-custom">Warning</span>
            <span class="badge-custom badge-danger-custom">Danger</span>
            <span class="badge bg-primary">Bootstrap Primary</span>
            <span class="badge bg-secondary">Bootstrap Secondary</span>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Demo Modal -->
    <div class="modal-custom" id="demoModal">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <h3>Demo Modal</h3>
                <button class="modal-close">&times;</button>
            </div>
            <p>This is a custom modal component. You can put any content here.</p>
            <div class="mt-4">
                <button class="btn-custom btn-primary-custom me-2">Save Changes</button>
                <button class="btn-custom btn-outline-custom" onclick="new Modal('demoModal').close()">Close</button>
            </div>
        </div>
    </div>
@endpush
