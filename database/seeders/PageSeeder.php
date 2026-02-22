<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '
                    <img src="https://placehold.co/1200x400/4c1d95/white?text=Our+Cosmic+Mission" class="img-fluid rounded shadow mb-4" alt="About Us">
                    <h3>Who We Are</h3>
                    <p>We are a team of passionate astrologers and tech enthusiasts dedicated to bringing the ancient wisdom of the stars to the modern world. Our platform connects thousands of users with expert guidance every day.</p>
                    <div class="row mt-5">
                        <div class="col-md-4 text-center">
                            <img src="https://placehold.co/200x200/4c1d95/white?text=Expert+Guidance" class="rounded-circle shadow mb-3" alt="Expert">
                            <h5>Expert Guidance</h5>
                            <p class="small">Verified astrologers from around the globe.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="https://placehold.co/200x200/7c3aed/white?text=Modern+Tech" class="rounded-circle shadow mb-3" alt="Tech">
                            <h5>Modern Tech</h5>
                            <p class="small">Daily updates and real-time consultations.</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="https://placehold.co/200x200/a78bfa/white?text=Secure+Data" class="rounded-circle shadow mb-3" alt="Secure">
                            <h5>Secure Data</h5>
                            <p class="small">Your privacy is our top priority.</p>
                        </div>
                    </div>'
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3>Get in Touch</h3>
                            <p>Have questions about your reading or our platform? Our support team is here to help you 24/7.</p>
                            <ul class="list-unstyled mt-4">
                                <li class="mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i> 123 Celestial way, Galaxy Tower, Star City</li>
                                <li class="mb-3"><i class="bi bi-telephone-fill text-primary me-2"></i> +1 (555) 789-0123</li>
                                <li class="mb-3"><i class="bi bi-envelope-fill text-primary me-2"></i> support@astrology-portal.com</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <img src="https://placehold.co/600x400/7c3aed/white?text=Contact+Us+Support" class="img-fluid rounded shadow" alt="Contact">
                        </div>
                    </div>'
            ],
            [
                'title' => 'Policy',
                'slug' => 'policy',
                'content' => '
                    <img src="https://placehold.co/1200x200/4c1d95/white?text=Privacy+Policy" class="img-fluid rounded mb-4 shadow-sm" alt="Policy">
                    <h3>1. Information Collection</h3>
                    <p>We collect information you provide directly to us, such as when you create or modify your account, request on-demand services, contact customer support, or otherwise communicate with us.</p>
                    <h3>2. Use of Information</h3>
                    <p>We may use the information we collect about you to provide, maintain, and improve our services, including, for example, to facilitate payments, send receipts, and provide products and services you request.</p>
                    <h3>3. Security</h3>
                    <p>We take reasonable measures to help protect information about you from loss, theft, misuse and unauthorized access, disclosure, alteration and destruction.</p>'
            ],
            [
                'title' => 'Terms & Condition',
                'slug' => 'terms-condition',
                'content' => '
                    <h3>1. Acceptance of Terms</h3>
                    <p>By accesssing this website, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws.</p>
                    <h3>2. Use License</h3>
                    <p>Permission is granted to temporarily download one copy of the materials (information or software) on our web site for personal, non-commercial transitory viewing only.</p>
                    <img src="https://placehold.co/1200x300/a78bfa/white?text=Legal+Terms" class="img-fluid rounded my-4 opacity-75" alt="Terms">'
            ],
            [
                'title' => 'Careers',
                'slug' => 'careers',
                'content' => '
                    <div class="text-center py-4">
                        <h3>Join Our Cosmic Team</h3>
                        <p class="lead">We are always looking for stellar talent to join our growing family.</p>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="card p-4 border shadow-none bg-light h-100">
                                <h5 class="text-primary fw-bold">Senior Astrologer</h5>
                                <p class="mb-3"><i class="bi bi-geo-alt"></i> Remote | Full-time</p>
                                <p class="small">Seeking an expert with 10+ years of Vedic astrology experience to provide high-level consultations.</p>
                                <button class="btn btn-outline-primary btn-sm mt-auto w-fit">Apply Now</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card p-4 border shadow-none bg-light h-100">
                                <h5 class="text-primary fw-bold">Full Stack Developer</h5>
                                <p class="mb-3"><i class="bi bi-geo-alt"></i> Hybrid | Star City</p>
                                <p class="small">Work on our real-time chat and call processing engine using Laravel and Twilio.</p>
                                <button class="btn btn-outline-primary btn-sm mt-auto w-fit">Apply Now</button>
                            </div>
                        </div>
                    </div>
                    <img src="https://placehold.co/1200x400/4c1d95/white?text=Work+with+Us" class="img-fluid rounded shadow mt-5" alt="Careers">'
            ],
            [
                'title' => 'Disclaimer',
                'slug' => 'disclaimer',
                'content' => '
                    <div class="alert alert-warning border-0 shadow-sm mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Important:</strong> Astrology is for entertainment and guidance purposes only.
                    </div>
                    <p>The information provided on this platform is for educational and entertainment purposes only and is not intended to be a substitute for professional legal, financial, or medical advice.</p>
                    <p>Individual results may vary, and we do not guarantee the accuracy of predictions or guidance provided by our astrologers.</p>'
            ],
            [
                'title' => 'Sitemap',
                'slug' => 'sitemap',
                'content' => '
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold border-bottom pb-2">Main Links</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="/" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> Home</a></li>
                                <li class="mb-2"><a href="/astrologers" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> Browse Astrologers</a></li>
                                <li class="mb-2"><a href="/blog" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> Astrology Blog</a></li>
                                <li class="mb-2"><a href="/kundli" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> Free Kundli</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold border-bottom pb-2">Information Center</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><a href="/about-us" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> About Us</a></li>
                                <li class="mb-2"><a href="/contact-us" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> Contact Support</a></li>
                                <li class="mb-2"><a href="/policy" class="text-decoration-none"><i class="bi bi-chevron-right small"></i> Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>'
            ],
        ];

        foreach ($pages as $page) {
            \App\Models\Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
