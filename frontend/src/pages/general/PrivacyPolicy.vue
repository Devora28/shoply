<script setup>
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import {
  Shield, Lock, FileText, Cookie, UserCheck, Mail, ChevronRight, ScrollText,
} from '@lucide/vue';
const lastUpdated = 'January 15, 2025'
const sections = [
  { id: 'introduction', label: 'Introduction', icon: FileText },
  { id: 'information', label: 'Information We Collect', icon: Lock },
  { id: 'use', label: 'How We Use Information', icon: ScrollText },
  { id: 'sharing', label: 'Data Sharing', icon: UserCheck },
  { id: 'security', label: 'Data Security', icon: Shield },
  { id: 'rights', label: 'Your Rights', icon: UserCheck },
  { id: 'cookies', label: 'Cookies', icon: Cookie },
  { id: 'third-party', label: 'Third-Party Services', icon: Shield },
  { id: 'children', label: "Children's Privacy", icon: Lock },
  { id: 'changes', label: 'Changes to Policy', icon: ScrollText },
  { id: 'contact', label: 'Contact', icon: Mail },
]
const policyContent = {
  introduction: [
    'At Shoply, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.',
    'By using our website and services, you consent to the practices described in this policy. We encourage you to read this policy carefully to understand our policies and practices regarding your information.',
  ],
  information: [
    'We collect various types of information to provide and improve our services. The types of information we may collect include:',
    'Personal Information: Name, email address, phone number, shipping and billing address, and payment method details when you create an account or place an order.',
    'Usage Data: IP address, browser type, device information, pages visited, time spent on pages, and other diagnostic data collected automatically when you use our website.',
    'Cookies and Tracking Technologies: We use cookies and similar technologies to track activity on our website and store certain information to enhance your browsing experience.',
  ],
  use: [
    'We use the information we collect for various purposes, including:',
    'Processing and fulfilling your orders, including shipping, returns, and customer support.',
    'Communicating with you about your account, orders, promotions, and updates to our services.',
    'Improving our website, products, and services through analytics and user feedback.',
    'Detecting, preventing, and addressing technical issues, fraud, and security concerns.',
    'Complying with legal obligations and enforcing our terms and conditions.',
  ],
  sharing: [
    'We do not sell, trade, or rent your personal information to third parties. However, we may share your information in the following situations:',
    'Service Providers: We may share your information with trusted third-party service providers who help us operate our website, process payments, deliver orders, and provide customer support.',
    'Legal Requirements: We may disclose your information if required by law, court order, or government regulation, or if we believe in good faith that such disclosure is necessary to protect our rights or safety.',
    'Business Transfers: In the event of a merger, acquisition, or asset sale, your information may be transferred to the acquiring entity, subject to the protections outlined in this policy.',
  ],
  security: [
    'We implement a variety of security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. These measures include:',
    'SSL encryption for all data transmitted between your browser and our servers.',
    'Secure data storage with industry-standard encryption protocols.',
    'Regular security audits and vulnerability assessments.',
    'Strict access controls limiting information access to authorized personnel only.',
    'Despite our efforts, no method of transmission over the internet or electronic storage is 100% secure. We cannot guarantee absolute security but strive to protect your information to the best of our ability.',
  ],
  rights: [
    'Depending on your location, you may have certain rights regarding your personal information, including:',
    'The right to access and receive a copy of your personal information.',
    'The right to request correction of inaccurate or incomplete information.',
    'The right to request deletion of your personal information, subject to legal obligations.',
    'The right to opt out of certain data processing activities and marketing communications.',
    'The right to restrict or object to the processing of your information.',
    'To exercise any of these rights, please contact us using the information provided in the Contact section below.',
  ],
  cookies: [
    'We use cookies and similar tracking technologies to enhance your experience on our website. Cookies are small data files stored on your device that help us:',
    'Remember your preferences and shopping cart contents.',
    'Authenticate your sessions and maintain account security.',
    'Analyze website traffic and user behavior to improve our services.',
    'Serve relevant advertisements and promotional content.',
    'You can control and manage cookies through your browser settings. However, disabling cookies may affect certain features of our website.',
  ],
  'third-party': [
    'Our website may use third-party services that collect, monitor, and analyze information, including:',
    'Payment processors (e.g., Stripe, PayPal) to securely handle transactions.',
    'Analytics providers (e.g., Google Analytics) to understand website usage.',
    'Shipping carriers to track and deliver your orders.',
    'Social media platforms for social sharing and advertising features.',
    'Each third-party service has its own privacy policy governing how they handle your information. We encourage you to review their policies.',
  ],
  children: [
    'Our website and services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children under 13. If you believe we have inadvertently collected such information, please contact us immediately, and we will take steps to delete it.',
    'Parents and guardians should monitor their children\'s online activities and supervise their use of our website to ensure compliance with this policy.',
  ],
  changes: [
    'We reserve the right to update or modify this Privacy Policy at any time. Any changes will be posted on this page with an updated "Last revised" date. We encourage you to review this policy periodically to stay informed about how we protect your information.',
    'If we make material changes to this policy, we will notify you through a prominent notice on our website or via email, depending on the nature of the changes.',
  ],
  contact: [
    'If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:',
    'Email: privacy@shoply.com',
    'Phone: +1 800 555 0100',
    'Address: 123 Commerce St, Portland, OR 97201',
    'Our data protection team will respond to your inquiry within 48 hours.',
  ],
}
function scrollToSection(id) {
  const el = document.getElementById(id)
  if (el) {
    el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <!-- BREADCRUMB -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
      <Breadcrumb :items="['Privacy Policy']" />
    </div>

    <!-- HEADER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 text-center">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl gradient-primary text-white mb-5">
        <Shield class="w-8 h-8" />
      </div>
      <h1 class="text-4xl sm:text-5xl font-bold text-ink-900 mb-3 tracking-tight">Privacy Policy</h1>
      <p class="text-ink-500 max-w-2xl mx-auto">
        Your privacy matters to us. Learn how we collect, use, and protect your personal information.
      </p>
      <p class="text-sm text-ink-400 mt-4">
        Last updated: <span class="font-medium text-ink-600">{{ lastUpdated }}</span>
      </p>
    </section>
    <!-- CONTENT -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="grid lg:grid-cols-4 gap-8">
        <!-- Table of Contents -->
        <aside class="lg:col-span-1">
          <div class="lg:sticky lg:top-6">
            <div class="card p-5">
              <h3 class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-4">Table of Contents</h3>
              <nav class="space-y-1">
                <button
                  v-for="section in sections"
                  :key="section.id"
                  @click="scrollToSection(section.id)"
                  class="flex items-center gap-2.5 w-full px-3 py-2 rounded-lg text-sm text-ink-600 hover:bg-primary-50 hover:text-primary-700 transition-colors text-left"
                >
                  <component :is="section.icon" class="w-4 h-4 shrink-0 text-ink-400" />
                  <span class="clamp-1">{{ section.label }}</span>
                  <ChevronRight class="w-3.5 h-3.5 ml-auto shrink-0 text-ink-300" />
                </button>
              </nav>
              <div class="divider my-4"></div>
              <router-link
                to="/contact"
                class="flex items-center gap-2 text-sm font-medium text-primary-700 hover:text-primary-800 transition-colors"
              >
                <Mail class="w-4 h-4" />
                Questions? Contact us
              </router-link>
            </div>
          </div>
        </aside>
        <!-- Policy sections -->
        <div class="lg:col-span-3 space-y-10">
          <section
            v-for="section in sections"
            :key="section.id"
            :id="section.id"
            class="scroll-mt-6"
          >
            <div class="flex items-center gap-3 mb-4">
              <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary-100 text-primary-700">
                <component :is="section.icon" class="w-5 h-5" />
              </div>
              <h2 class="text-xl sm:text-2xl font-bold text-ink-900">{{ section.label }}</h2>
            </div>
            <div class="card p-6 sm:p-8">
              <p
                v-for="(paragraph, i) in policyContent[section.id]"
                :key="i"
                class="text-ink-600 leading-relaxed mb-3 last:mb-0"
                :class="{ 'font-medium text-ink-700': i === 0 }"
              >
                {{ paragraph }}
              </p>
            </div>
          </section>
          <!-- Contact CTA -->
          <div class="relative overflow-hidden rounded-3xl gradient-primary text-white p-8 sm:p-10 text-center">
            <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2240%22 height=%2240%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Ccircle cx=%2220%22 cy=%2220%22 r=%221%22 fill=%22white%22/%3E%3C/svg%3E');"></div>
            <div class="relative">
              <h3 class="text-xl font-bold mb-2">Have privacy concerns?</h3>
              <p class="text-white/80 text-sm mb-6 max-w-md mx-auto">
                We take your privacy seriously. Reach out to our team with any questions or requests.
              </p>
              <router-link to="/contact" class="btn-accent btn-lg">
                <Mail class="w-5 h-5" />
                Contact Us
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
