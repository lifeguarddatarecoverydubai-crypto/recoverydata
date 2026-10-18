<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. Services from Recovery Data
        // -------------------------------------------------------------
        $services = [
            [
                'title' => 'Data Analytics & Business Intelligence',
                'slug' => 'data-analytics-business-intelligence',
                'tagline' => 'Transform Raw Data into Strategic UAE Market Advantage',
                'icon' => 'bar-chart-3',
                'summary' => 'We analyze and visualize your enterprise data to uncover insights, track performance, and support smarter, data-driven business decisions.',
                'content' => '<p>In today’s competitive UAE market, decisions based on intuition are no longer enough. Recovery Data turns your complex enterprise data into intuitive, real-time dashboards and predictive models that empower executives to lead with total clarity.</p>
<h3>What We Deliver</h3>
<p>From data warehouse architecture to interactive Power BI and Tableau dashboards, we build scalable analytics pipelines that unify data across marketing, sales, inventory, and finance.</p>
<ul>
    <li><strong>Executive KPI Dashboards:</strong> Unified single-pane-of-glass views for leadership.</li>
    <li><strong>Predictive Analytics:</strong> Machine learning forecasts for customer demand, churn, and revenue.</li>
    <li><strong>Data Governance & Compliance:</strong> Strict adherence to UAE Data Protection Law and cybersecurity best practices.</li>
</ul>',
                'features' => [
                    'Interactive Executive Dashboards',
                    'Predictive Sales & Demand Forecasting',
                    'Real-time Operational Performance Tracking',
                    'Cross-platform Data Pipeline Integration',
                    'UAE Compliance & Privacy Governance',
                ],
                'benefits' => [
                    '35% Faster Executive Decision Making',
                    '99.9% Data Accuracy Across Departments',
                    'Proactive Churn & Anomaly Detection',
                ],
                'order' => 1,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'Data Analytics Dubai',
                'meta_title' => 'Data Analytics & Business Intelligence Dubai | Recovery Data',
                'meta_description' => 'Drive smarter growth with enterprise Data Analytics and BI dashboards in UAE. Custom predictive models and real-time visualization by Recovery Data.',
                'meta_keywords' => 'data analytics dubai, business intelligence uae, power bi dashboards, predictive analytics',
                'seo_score' => 92,
                'readability_score' => 88,
            ],
            [
                'title' => 'Data Migration & Cloud Services',
                'slug' => 'data-migration-cloud-services',
                'tagline' => 'Seamless, Zero-Downtime Cloud Transition for Enterprises',
                'icon' => 'cloud',
                'summary' => 'We securely move your data to modern cloud environments and optimize systems for scalability, performance, security, and reliability.',
                'content' => '<p>Legacy servers holding back your innovation? Recovery Data delivers enterprise-grade cloud migration tailored for UAE businesses. Backed by 32+ years of cleanroom and infrastructure excellence, we ensure zero data loss, minimal downtime, and complete data sovereignty.</p>
<h3>Our Cloud Specializations</h3>
<p>Whether migrating to AWS, Microsoft Azure, Google Cloud, or hybrid UAE-hosted private clouds, our certified engineers architect architectures built for high availability and low latency.</p>
<ul>
    <li><strong>Lift-and-Shift or Cloud-Native Modernization:</strong> Strategy tailored to your workload criticality.</li>
    <li><strong>UAE Local Data Residency:</strong> Fully compliant with regional cloud sovereign regulations.</li>
    <li><strong>Cloud FinOps:</strong> Continuous cost optimization to reduce cloud infrastructure spend by up to 30%.</li>
</ul>',
                'features' => [
                    'Zero Data-Loss Migration Protocols',
                    'Multi-Cloud & Hybrid Cloud Architecture',
                    'UAE Data Residency Law Compliance',
                    'Automated Disaster Recovery & Backup',
                    'FinOps Cloud Cost Optimization',
                ],
                'benefits' => [
                    'Zero Disruption to Daily Operations',
                    'Up to 30% Infrastructure Cost Reduction',
                    '24/7 Managed Cloud Monitoring & Support',
                ],
                'order' => 2,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'Cloud Migration UAE',
                'meta_title' => 'Data Migration & Cloud Services UAE | Recovery Data',
                'meta_description' => 'Secure enterprise cloud migration in Dubai & UAE. Move data safely to AWS, Azure, and hybrid cloud with zero downtime by Recovery Data experts.',
                'meta_keywords' => 'cloud migration dubai, data migration uae, aws azure migration, cloud services dubai',
                'seo_score' => 94,
                'readability_score' => 90,
            ],
            [
                'title' => 'ERP Solutions',
                'slug' => 'erp-solutions',
                'tagline' => 'End-to-End Enterprise Resource Planning Aligned with UAE FTA',
                'icon' => 'layers',
                'summary' => 'We implement, customize, and manage ERP systems to streamline business processes, eliminate silos, improve efficiency, and drive sustainable growth.',
                'content' => '<p>Modern enterprises cannot thrive on disconnected spreadsheets and outdated software. Recovery Data delivers tailored ERP implementations that seamlessly tie together procurement, inventory, finance, human resources, and sales.</p>
<h3>Designed for the UAE Regulatory Landscape</h3>
<p>Our ERP implementations are engineered from day one to handle UAE Federal Tax Authority (FTA) requirements, VAT compliance, electronic invoicing, and regional banking interfaces.</p>
<ul>
    <li><strong>Core Financial Ledger & Invoicing:</strong> Automated VAT calculations and FTA-approved audit reports.</li>
    <li><strong>Supply Chain & Inventory:</strong> Multi-warehouse batch tracking and barcode scanning.</li>
    <li><strong>Seamless Third-Party Integrations:</strong> Connect your ERP with CRM, eCommerce, and banking APIs.</li>
</ul>',
                'features' => [
                    'FTA & UAE VAT Compliant Invoicing',
                    'Multi-Entity & Multi-Currency Accounting',
                    'Automated Supply Chain & Warehouse Management',
                    'Custom Workflow Approvals & Role Permissions',
                    'Seamless Integration with CRM & Banks',
                ],
                'benefits' => [
                    '100% Regulatory Audit Preparedness',
                    '40% Reduction in Manual Reconciliation Time',
                    'Real-Time Stock & Financial Visibility',
                ],
                'order' => 3,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'ERP Solutions Dubai',
                'meta_title' => 'ERP Solutions Dubai UAE | Recovery Data',
                'meta_description' => 'Complete ERP implementation and customization in UAE. FTA VAT compliant, inventory management, and automated finance workflows.',
                'meta_keywords' => 'erp solutions dubai, erp software uae, fta vat erp, enterprise resource planning',
                'seo_score' => 91,
                'readability_score' => 87,
            ],
            [
                'title' => 'Automations & AI Agents',
                'slug' => 'automations-ai-agents',
                'tagline' => 'Intelligent Autonomous AI Agents That Work For You 24/7',
                'icon' => 'bot',
                'summary' => 'We create smart automations and autonomous AI-driven agents to streamline repetitive tasks, boost employee productivity, and accelerate decision-making.',
                'content' => '<p>Step beyond simple rule-based automation. Recovery Data builds cognitive AI agents that understand context, read unstructured documents, make decisions, and execute multi-step workflows across your business applications.</p>
<h3>Where AI Agents Drive Immediate ROI</h3>
<p>From parsing complex commercial trade invoices to orchestrating customer support queries across WhatsApp, email, and CRM, our AI agents free your workforce to focus on high-value strategy.</p>
<ul>
    <li><strong>Document AI:</strong> Instant extraction from Arabic & English contracts, invoices, and IDs.</li>
    <li><strong>Autonomous Workflow Bots:</strong> Robotic and generative agents that execute actions in your ERP and CRM.</li>
    <li><strong>Enterprise Knowledge Bots:</strong> Chat with your company SOPs and private data with strict access controls.</li>
</ul>',
                'features' => [
                    'Autonomous Agentic Workflow Orchestration',
                    'Bilingual Arabic & English NLP Processing',
                    'Smart OCR & Document Intelligence',
                    'WhatsApp Business AI Automation',
                    'Enterprise Guardrails & Privacy First',
                ],
                'benefits' => [
                    '70% Cut in Repetitive Administrative Workload',
                    'Instant 24/7 Customer and Employee Responses',
                    'Zero Human Error in Cross-System Data Entry',
                ],
                'order' => 4,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'AI Automation UAE',
                'meta_title' => 'Automations & AI Agents Dubai UAE | Recovery Data',
                'meta_description' => 'Deploy autonomous AI agents and intelligent process automation in UAE. Bilingual Arabic-English workflows, document intelligence, and ERP bot integration.',
                'meta_keywords' => 'ai agents dubai, automation uae, process automation, ai workflow agents, llm enterprise',
                'seo_score' => 95,
                'readability_score' => 92,
            ],
            [
                'title' => 'Custom Software Development',
                'slug' => 'custom-software-development',
                'tagline' => 'Bespoke Web, Mobile & Cloud Platforms Engineered to Scale',
                'icon' => 'code-2',
                'summary' => 'We design and build bespoke software solutions to meet your unique business needs, elevate customer experiences, and drive technological differentiation.',
                'content' => '<p>Off-the-shelf software doesn’t always fit your proprietary operational workflows. Recovery Data provides end-to-end custom software engineering, from concept and UI/UX design to robust backend architecture and cloud deployment.</p>
<h3>Modern Tech Stack & Architecture</h3>
<p>We leverage high-performance frameworks like Laravel, Vue, React, Python, and cloud-native microservices to deliver reliable, lightning-fast digital products that withstand heavy enterprise loads.</p>',
                'features' => [
                    'Custom Web Applications & Enterprise Portals',
                    'iOS & Android Native and Cross-Platform Apps',
                    'Secure REST & GraphQL API Architecture',
                    'Rigorous QA, Unit Testing & Pentesting',
                    'Ongoing DevOps, Maintenance & SLA',
                ],
                'benefits' => [
                    'Tailored 100% to Your Business Logic',
                    'Full Code Ownership with No Vendor Lock-in',
                    'Enterprise Scalability from Day One',
                ],
                'order' => 5,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'Custom Software Development Dubai',
                'meta_title' => 'Custom Software Development Dubai | Recovery Data',
                'meta_description' => 'Bespoke web and mobile software development in Dubai UAE. High-performance, secure enterprise software engineered by Recovery Data.',
                'meta_keywords' => 'custom software development dubai, web development uae, mobile apps dubai, enterprise software',
                'seo_score' => 90,
                'readability_score' => 86,
            ],
            [
                'title' => 'AI-Powered Insights & Dashboards',
                'slug' => 'ai-powered-insights-dashboards',
                'tagline' => 'Next-Generation Real-Time Analytics Powered by Generative AI',
                'icon' => 'sparkles',
                'summary' => 'Leverage conversational AI and real-time visualization to ask plain-English questions about your business and receive instant, verifiable answers.',
                'content' => '<p>Say goodbye to waiting days for analysts to generate reports. With Recovery Data’s conversational BI dashboards, executives can type: <em>"What was our most profitable branch in Abu Dhabi this quarter?"</em> and get instant visual charts with deep predictive breakdowns.</p>',
                'features' => [
                    'Natural Language Querying (Ask in English/Arabic)',
                    'Automated Executive Anomaly Alerts',
                    'Real-Time Cross-Branch Performance Radar',
                    'Role-Based Granular Visual Permissions',
                ],
                'benefits' => [
                    'Eliminate Reporting Bottlenecks',
                    'Instant Discovery of Hidden Margin Opportunities',
                    'C-Suite Ready Visuals in Seconds',
                ],
                'order' => 6,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'AI Dashboards Dubai',
                'meta_title' => 'AI-Powered Insights & Dashboards UAE | Recovery Data',
                'meta_description' => 'Next-generation AI executive dashboards in UAE. Conversational data insights and automated anomaly alerts for leading enterprises.',
                'meta_keywords' => 'ai dashboards, conversational bi, executive reporting uae, business intelligence dubai',
                'seo_score' => 89,
                'readability_score' => 85,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // -------------------------------------------------------------
        // 2. Products (HRMS, CRM, Cloud ERP, AI Agent Studio)
        // -------------------------------------------------------------
        $products = [
            [
                'title' => 'Recovery Data HRMS',
                'slug' => 'recovery-data-hrms',
                'tagline' => 'New-Age AI-Native Human Capital Management Built for UAE Enterprises',
                'badge' => 'Flagship HCM Suite',
                'category' => 'Human Resources & Payroll',
                'icon' => 'users-round',
                'summary' => 'Comprehensive HRMS & WPS-compliant payroll platform. Automate geofenced attendance, talent lifecycle, appraisals, and employee self-service in UAE.',
                'content' => '<p>Inspired by global HCM leaders and tailored specifically to the UAE and GCC labour ecosystem, <strong>Recovery Data HRMS</strong> is the complete, modern platform for workforce management.</p>
<h3>Engineered for UAE Labour Regulations & WPS</h3>
<p>Say goodbye to manual calculations and compliance worries. Recovery Data HRMS generates UAE Wages Protection System (WPS) SIF files in a single click, calculates End of Service Gratuity (EOSB) strictly per UAE Labour Law (Federal Decree Law No. 33), and tracks visa and Emirates ID expirations automatically.</p>
<h3>Core Modules & Capabilities</h3>
<ul>
    <li><strong>Smart Attendance & Mobile Geofencing:</strong> Facial recognition check-in, GPS geofencing for remote or site workers, and automated shift scheduling.</li>
    <li><strong>WPS Compliant UAE Payroll:</strong> Automated allowances, overtime calculations, bank SIF export, and electronic digital payslips.</li>
    <li><strong>Employee Self-Service (ESS) Mobile Portal:</strong> Empower employees to request leaves, claim expenses, and view documents from their phones.</li>
    <li><strong>Performance & OKRs:</strong> 360-degree reviews, continuous feedback loops, and objective alignment.</li>
    <li><strong>AI Talent Intelligence:</strong> Smart resume parsing, candidate scoring, and proactive flight-risk detection.</li>
</ul>',
                'modules' => [
                    [
                        'name' => 'WPS Payroll & Gratuity Engine',
                        'desc' => 'Instant UAE WPS SIF file generation, automated gratuity (EOSB) calculations, and electronic payslips.',
                    ],
                    [
                        'name' => 'Smart Attendance & Geofencing',
                        'desc' => 'GPS-verified mobile check-in, biometric syncing, and dynamic shift roster management.',
                    ],
                    [
                        'name' => 'Employee Self-Service (ESS)',
                        'desc' => 'Mobile-first leave requests, loan applications, expense receipts, and document management.',
                    ],
                    [
                        'name' => 'Talent Acquisition & Onboarding',
                        'desc' => 'AI resume parsing, structured interview scorecards, and paperless digital onboarding.',
                    ],
                    [
                        'name' => 'Performance & OKR Tracking',
                        'desc' => 'Continuous goal alignment, 360-degree feedback, and merit-based appraisal workflows.',
                    ],
                ],
                'highlights' => [
                    '100% UAE Labour Law & WPS Compliant',
                    'Zero Payroll Calculation Errors',
                    '95% Faster Leave & Expense Approvals',
                    'Bilingual Arabic & English Interface',
                    'Mobile iOS & Android Employee App',
                ],
                'demo_url' => '/contact?product=hrms',
                'brochure_url' => '#',
                'order' => 1,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'HRMS Software Dubai UAE',
                'meta_title' => 'Recovery Data HRMS - Best HR & Payroll Software in UAE & Dubai',
                'meta_description' => 'Modern AI-native HRMS & Payroll software for UAE enterprises. 100% WPS compliant, attendance geofencing, employee self-service, and EOSB calculations.',
                'meta_keywords' => 'hrms dubai, hr software uae, wps payroll software, attendance software dubai, darwinbox alternative uae',
                'seo_score' => 96,
                'readability_score' => 92,
            ],
            [
                'title' => 'Recovery Data CRM',
                'slug' => 'recovery-data-crm',
                'tagline' => 'AI-Powered Omnichannel Sales Acceleration & Customer Engagement',
                'badge' => 'Sales & Revenue Suite',
                'category' => 'Sales & Customer Relationship',
                'icon' => 'target',
                'summary' => 'Intelligent CRM built for modern sales teams. Seamless WhatsApp Business Cloud integration, visual pipeline management, automated lead scoring, and deal forecasting.',
                'content' => '<p>In the Middle East, sales move through relationships and fast messaging channels like WhatsApp. <strong>Recovery Data CRM</strong> unifies inbound inquiries from web forms, WhatsApp, phone, and email into an intuitive visual pipeline that guides reps from lead to signed contract.</p>
<h3>Omnichannel WhatsApp Cloud Integration</h3>
<p>Direct integration with the WhatsApp Business API allows your team to communicate with clients, share proposals, trigger automated follow-up sequences, and maintain full CRM audit records without switching apps.</p>
<h3>Features That Empower High-Velocity Teams</h3>
<ul>
    <li><strong>Predictive Lead Scoring:</strong> AI ranks leads based on engagement, company size, and conversion probability.</li>
    <li><strong>Visual Drag-and-Drop Pipeline:</strong> Multiple customized sales funnels for B2B, enterprise deals, and retail inquiries.</li>
    <li><strong>Automated Quotations & Invoicing:</strong> Generate branded quotes in seconds with automated VAT calculations.</li>
    <li><strong>Executive Deal Forecasting:</strong> Reliable AI revenue forecasts with win-loss analytics.</li>
</ul>',
                'modules' => [
                    [
                        'name' => 'WhatsApp Cloud API Integration',
                        'desc' => 'Direct two-way messaging, automated template triggers, and shared team inbox directly in the CRM.',
                    ],
                    [
                        'name' => 'Visual Deal Pipeline (Kanban)',
                        'desc' => 'Custom sales stages, automated task reminders, and visual deal value distribution.',
                    ],
                    [
                        'name' => 'AI Lead Qualification & Scoring',
                        'desc' => 'Prioritize hottest prospects using behavioral analytics and engagement scoring.',
                    ],
                    [
                        'name' => 'Document & Quotation Generator',
                        'desc' => 'Generate UAE VAT-compliant quotes and contracts with digital signature support.',
                    ],
                    [
                        'name' => 'Omnichannel Helpdesk & Tickets',
                        'desc' => 'Post-sale customer ticketing and SLA management to retain high-value accounts.',
                    ],
                ],
                'highlights' => [
                    'Built-in Official WhatsApp Business API',
                    '35% Higher Lead-to-Deal Conversion',
                    'Real-Time Deal Tracking & Stage Alerts',
                    'Complete Arabic & English Communication',
                    'Seamless ERP & Accounting Handshake',
                ],
                'demo_url' => '/contact?product=crm',
                'brochure_url' => '#',
                'order' => 2,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'CRM Software Dubai UAE',
                'meta_title' => 'Recovery Data CRM - Enterprise Sales & WhatsApp CRM in UAE',
                'meta_description' => 'Accelerate revenue with Recovery Data CRM. WhatsApp Business integration, visual deal pipeline, automated follow-ups, and AI lead scoring for UAE businesses.',
                'meta_keywords' => 'crm software dubai, whatsapp crm uae, sales pipeline software, customer relationship management dubai',
                'seo_score' => 95,
                'readability_score' => 90,
            ],
            [
                'title' => 'Recovery Data Cloud ERP',
                'slug' => 'recovery-data-cloud-erp',
                'tagline' => 'Next-Gen Unified Enterprise Resource Planning with FTA Invoicing',
                'badge' => 'Enterprise Operations',
                'category' => 'Enterprise Resource Planning',
                'icon' => 'layers-3',
                'summary' => 'Unified cloud platform linking inventory, procurement, finance, and accounting. 100% compliant with UAE Federal Tax Authority (FTA) regulations.',
                'content' => '<p>Say goodbye to fragmented point solutions. <strong>Recovery Data Cloud ERP</strong> unites every core department into a single source of truth. Manage warehouses in Dubai, sales branches in Abu Dhabi, and overseas procurement seamlessly.</p>
<h3>Built for the UAE Tax & Financial Standard</h3>
<p>Fully compliant with UAE FTA electronic tax invoices, VAT returns (Form 201), and multi-currency banking reconciliations with major UAE financial institutions.</p>',
                'modules' => [
                    [
                        'name' => 'General Ledger & VAT Accounting',
                        'desc' => 'Automated journal entries, VAT return generation, and multi-currency bank reconciliation.',
                    ],
                    [
                        'name' => 'Inventory & Multi-Warehouse',
                        'desc' => 'Barcode scanning, batch expiration dates, automated reorder triggers, and stock transfers.',
                    ],
                    [
                        'name' => 'Procurement & Vendor Portal',
                        'desc' => 'Purchase requisition approval hierarchies, vendor rate contracts, and receipt verification.',
                    ],
                    [
                        'name' => 'Billing & FTA Invoicing',
                        'desc' => 'QR-code compliant electronic tax invoices, credit notes, and automated payment reminders.',
                    ],
                ],
                'highlights' => [
                    '100% FTA UAE VAT & Electronic Invoicing Compliant',
                    'Multi-Warehouse & Multi-Branch Real-Time Stock',
                    'Custom Multi-Level Approval Matrices',
                    'Native Mobile Barcode Scanning',
                ],
                'demo_url' => '/contact?product=erp',
                'brochure_url' => '#',
                'order' => 3,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'Cloud ERP Dubai UAE',
                'meta_title' => 'Recovery Data Cloud ERP - UAE FTA Compliant Enterprise ERP Software',
                'meta_description' => 'Unify operations with Recovery Data Cloud ERP. Complete inventory, procurement, and accounting software compliant with UAE Federal Tax Authority VAT laws.',
                'meta_keywords' => 'cloud erp uae, erp dubai, fta vat compliant erp, warehouse management software dubai',
                'seo_score' => 93,
                'readability_score' => 89,
            ],
            [
                'title' => 'TrainIQ',
                'slug' => 'trainiq',
                'tagline' => 'SOP-Driven Learning Management System | Turn SOPs into Measurable Readiness',
                'badge' => 'SOP-Driven LMS',
                'category' => 'Enterprise Learning & SOP Automation',
                'icon' => 'graduation-cap',
                'summary' => 'TrainIQ transforms Standard Operating Procedures into structured digital courses — eliminating repetitive training, shortening onboarding time, and verifying operational readiness from day one.',
                'content' => '<p>When institutional knowledge lives in conversations and printed binders, onboarding leaks time, consistency, and confidence. <strong>TrainIQ</strong> is the B2B SOP-driven learning management platform engineered to convert your procedures into structured, repeatable, audit-ready digital courses.</p>
<h3>Turn Every SOP into Measurable Readiness at Scale</h3>
<p>Generic LMS platforms optimize for course catalogs. TrainIQ optimizes for operational execution. Convert existing Standard Operating Procedures directly into digital courses with structured chapters, contextual objectives, and assessment verification gates — no instructional design overhead required.</p>
<h3>Sequential Learning Control with Locked Chapter Gates</h3>
<p>Chapters unlock strictly after previous assessments are passed with defined minimum passing thresholds. Employees cannot skip steps, ensuring every procedure is absorbed in the correct operational sequence.</p>
<h3>Department-Wise Independent Learning Ecosystems</h3>
<p>TrainIQ mirrors your actual organization. Sales, Marketing, Technical, Support, HR, Operations, Finance, and Branches each manage their own SOP courses, assignments, and learner paths with independent role-based visibility.</p>
<p>Explore the live platform and enterprise rollout solutions at <a href="https://trainiq.ae/" target="_blank" class="text-brand-600 underline font-semibold">trainiq.ae</a>.</p>',
                'modules' => [
                    [
                        'name' => 'SOP-Driven Course Builder',
                        'desc' => 'Convert existing Standard Operating Procedures into digital courses with chapters and objectives — zero coding or external tools required.',
                    ],
                    [
                        'name' => 'Sequential Learning & Locked Gates',
                        'desc' => 'Chapters unlock only after passing previous assessments with score-based progression control.',
                    ],
                    [
                        'name' => 'Department-Level Ecosystems',
                        'desc' => 'Independent learning environments for Sales, Technical, Operations, HR, Support, Finance, and Branches.',
                    ],
                    [
                        'name' => 'Assessment Engine & Comprehension Tests',
                        'desc' => 'MCQs, real-world scenario evaluations, and SOP comprehension checks that verify understanding, not just views.',
                    ],
                    [
                        'name' => 'Role-Based Dashboards & Multi-Org',
                        'desc' => 'Tailored interfaces and granular controls for Organization Admins, Department Managers, and Learners across branches.',
                    ],
                    [
                        'name' => 'Real-Time Readiness Analytics',
                        'desc' => 'Track onboarding duration, department readiness, pass rates, and competency mapping with audit-ready reporting.',
                    ],
                ],
                'highlights' => [
                    'SOP-Driven Course Creation with Chapter & Milestone Architecture',
                    'Enforced Sequential Learning with Score-Based Gate Unlocking',
                    'Department-Wise Isolated Ecosystems (Sales, Tech, Ops, HR, Branches)',
                    'Real-World Scenario Questions & SOP Comprehension Assessment Engine',
                    'Real-Time Department Readiness Reports & Operational Audit Trails',
                    'Multi-Company, Branch & Franchise Multi-Tenant Management',
                ],
                'demo_url' => 'https://trainiq.ae/',
                'brochure_url' => 'https://trainiq.ae/',
                'order' => 4,
                'is_featured' => true,
                'status' => 'published',
                'focus_keyword' => 'SOP Learning Management System UAE',
                'meta_title' => 'TrainIQ - SOP-Driven Learning Management System | Recovery Data',
                'meta_description' => 'TrainIQ transforms Standard Operating Procedures into structured digital courses with locked chapters, assessment gates, and real-time operational readiness tracking.',
                'meta_keywords' => 'trainiq, sop lms uae, learning management system dubai, employee onboarding platform uae, enterprise training software',
                'seo_score' => 96,
                'readability_score' => 93,
            ],
        ];

        Product::whereIn('slug', [
            'upstart-ai-agent-studio',
            'upstart-hrms',
            'upstart-crm',
            'upstart-cloud-erp',
        ])->delete();

        foreach ($products as $data) {
            Product::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
