<?php

namespace Database\Seeders;

use App\Models\Analysis;
use App\Models\Criterion;
use App\Models\FollowUp;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Response;
use App\Models\Tender;
use App\Models\TenderDocument;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Erik van der Berg',
            'email' => 'admin@tenderly.com',
            'password' => Hash::make('password'),
        ]);

        // Tender 1: Active with full data
        $t1 = Tender::create([
            'user_id' => $user->id,
            'name' => 'Municipal Digital Transformation Platform',
            'description' => 'Development of a comprehensive digital platform to modernize citizen services for the Municipality of Rotterdam. The platform must handle permit applications, service requests, and real-time status tracking.',
            'objectives' => "Reduce citizen wait times by 60%\nAutomate 80% of standard permit workflows\nFull WCAG 2.1 AA accessibility compliance\nIntegrate with existing BRP and BAG registries",
            'focus_themes' => "Citizen experience, Security, Accessibility, Integration, Scalability",
            'context_notes' => 'Previous vendor contract expired. Need seamless migration of 45,000 active citizen accounts. Dutch language primary, English secondary.',
            'deadline' => now()->addDays(14),
            'status' => 'active',
        ]);

        TenderDocument::create([
            'tender_id' => $t1->id,
            'filename' => 'tender-documents/1/scope-of-work.pdf',
            'original_name' => 'Rotterdam_Digital_Platform_SOW_v2.1.pdf',
            'mime_type' => 'application/pdf',
            'size' => 2457600,
            'processing_status' => 'completed',
            'extracted_text' => 'Comprehensive scope of work covering 12 modules: citizen portal, permit management, document processing, notification system, reporting dashboard, API gateway, identity management, payment processing, scheduling, case management, analytics, and mobile responsiveness.',
        ]);

        TenderDocument::create([
            'tender_id' => $t1->id,
            'filename' => 'tender-documents/1/technical-requirements.pdf',
            'original_name' => 'Technical_Requirements_Specification.pdf',
            'mime_type' => 'application/pdf',
            'size' => 1843200,
            'processing_status' => 'completed',
            'extracted_text' => 'Technical requirements including cloud-native architecture, Kubernetes orchestration, PostgreSQL database, REST and GraphQL APIs, OAuth 2.0 / OIDC authentication, GDPR compliance, 99.9% SLA, sub-200ms response times.',
        ]);

        // Criteria for Tender 1
        $c1 = Criterion::create(['tender_id' => $t1->id, 'name' => 'Technical Architecture', 'description' => 'Quality and suitability of proposed technical solution', 'weight' => 30, 'sort_order' => 1]);
        $c2 = Criterion::create(['tender_id' => $t1->id, 'name' => 'Team & Organization', 'description' => 'Team composition, experience, and organizational capability', 'weight' => 20, 'sort_order' => 2]);
        $c3 = Criterion::create(['tender_id' => $t1->id, 'name' => 'Quality Assurance', 'description' => 'Testing methodology, standards compliance, and quality metrics', 'weight' => 20, 'sort_order' => 3]);
        $c4 = Criterion::create(['tender_id' => $t1->id, 'name' => 'Risk Management', 'description' => 'Risk identification, mitigation strategies, and contingency planning', 'weight' => 15, 'sort_order' => 4]);
        $c5 = Criterion::create(['tender_id' => $t1->id, 'name' => 'Timeline & Planning', 'description' => 'Project schedule feasibility and milestone definition', 'weight' => 15, 'sort_order' => 5]);

        // Sub-criteria
        Criterion::create(['tender_id' => $t1->id, 'parent_id' => $c1->id, 'name' => 'Cloud Infrastructure', 'weight' => 10, 'sort_order' => 1]);
        Criterion::create(['tender_id' => $t1->id, 'parent_id' => $c1->id, 'name' => 'Security Design', 'weight' => 10, 'sort_order' => 2]);
        Criterion::create(['tender_id' => $t1->id, 'parent_id' => $c1->id, 'name' => 'API Strategy', 'weight' => 10, 'sort_order' => 3]);

        // Questions
        $questions = [];

        $questions[] = Question::create(['criterion_id' => $c1->id, 'question_text' => 'What specific technologies and frameworks do you propose for this solution, and why are they the best fit for a municipal platform?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 1]);
        $questions[] = Question::create(['criterion_id' => $c1->id, 'question_text' => 'How will your proposed architecture handle scaling from 45,000 to 200,000+ citizen accounts?', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 2]);
        $questions[] = Question::create(['criterion_id' => $c1->id, 'question_text' => 'Describe your approach to ensuring data security and GDPR compliance throughout the system.', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 3]);
        $questions[] = Question::create(['criterion_id' => $c1->id, 'question_text' => 'What is your integration strategy for connecting with existing BRP and BAG registry systems?', 'priority' => 'high', 'source' => 'manual', 'sort_order' => 4]);

        $questions[] = Question::create(['criterion_id' => $c2->id, 'question_text' => 'Describe the core team members and their relevant experience with public sector digital platforms.', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 1]);
        $questions[] = Question::create(['criterion_id' => $c2->id, 'question_text' => 'How will knowledge transfer be handled to ensure the municipality can manage the platform independently?', 'priority' => 'normal', 'source' => 'ai_generated', 'sort_order' => 2]);
        $questions[] = Question::create(['criterion_id' => $c2->id, 'question_text' => 'Provide examples of similar municipal or government projects your team has successfully delivered.', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 3]);

        $questions[] = Question::create(['criterion_id' => $c3->id, 'question_text' => 'What quality assurance processes and standards will be applied throughout the project lifecycle?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 1]);
        $questions[] = Question::create(['criterion_id' => $c3->id, 'question_text' => 'How will you ensure WCAG 2.1 AA accessibility compliance across all citizen-facing interfaces?', 'priority' => 'critical', 'source' => 'manual', 'sort_order' => 2]);
        $questions[] = Question::create(['criterion_id' => $c3->id, 'question_text' => 'Describe your automated testing strategy including unit, integration, and end-to-end testing.', 'priority' => 'normal', 'source' => 'ai_generated', 'sort_order' => 3]);

        $questions[] = Question::create(['criterion_id' => $c4->id, 'question_text' => 'Identify the top 5 risks you foresee for this project and your proposed mitigation strategies.', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $questions[] = Question::create(['criterion_id' => $c4->id, 'question_text' => 'How will you handle scope changes or requirement modifications during the project?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 2]);
        $questions[] = Question::create(['criterion_id' => $c4->id, 'question_text' => 'What contingency plans are in place for critical path delays, especially around registry integration?', 'priority' => 'high', 'source' => 'manual', 'sort_order' => 3]);

        $questions[] = Question::create(['criterion_id' => $c5->id, 'question_text' => 'Provide a detailed project timeline with key milestones and deliverables.', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 1]);
        $questions[] = Question::create(['criterion_id' => $c5->id, 'question_text' => 'What are the critical dependencies that could impact the schedule?', 'priority' => 'normal', 'source' => 'ai_generated', 'sort_order' => 2]);

        // Participants
        $p1 = Participant::create(['tender_id' => $t1->id, 'name' => 'Dr. Sophia Jansen', 'email' => 'sophia.jansen@techconsult.nl', 'role' => 'Cloud Architect', 'token' => 'demo_token_sophia_01234567890123456789012345', 'status' => 'submitted', 'invited_at' => now()->subDays(7), 'last_active_at' => now()->subDays(2), 'submitted_at' => now()->subDays(2)]);
        $p2 = Participant::create(['tender_id' => $t1->id, 'name' => 'Marcus de Vries', 'email' => 'marcus@govtech.eu', 'role' => 'Security Specialist', 'token' => 'demo_token_marcus_01234567890123456789012345', 'status' => 'submitted', 'invited_at' => now()->subDays(7), 'last_active_at' => now()->subDays(1), 'submitted_at' => now()->subDays(1)]);
        $p3 = Participant::create(['tender_id' => $t1->id, 'name' => 'Lena Bakker', 'email' => 'l.bakker@digitalservices.nl', 'role' => 'UX Lead', 'token' => 'demo_token_lena_012345678901234567890123456', 'status' => 'active', 'invited_at' => now()->subDays(7), 'last_active_at' => now()->subHours(6)]);
        $p4 = Participant::create(['tender_id' => $t1->id, 'name' => 'Thomas Vermeer', 'email' => 'thomas@procure-experts.com', 'role' => 'Project Manager', 'token' => 'demo_token_thomas_0123456789012345678901234', 'status' => 'invited', 'invited_at' => now()->subDays(5)]);

        // Responses from Sophia (all questions answered)
        foreach ($questions as $i => $q) {
            $answers = [
                'We propose a cloud-native architecture using Kubernetes on Azure (AKS) for container orchestration, with a .NET Core microservices backend and React frontend. Azure is preferred for its strong presence in the EU government sector with certified data centers in the Netherlands. Key benefits: native integration with Azure AD for government SSO, Azure API Management for the gateway layer, and Azure DevOps for CI/CD pipelines.',
                'Our architecture is designed for horizontal scaling from the ground up. Each microservice runs in auto-scaling Kubernetes pods with HPA (Horizontal Pod Autoscaler) configured for CPU and memory thresholds. The database layer uses Azure Database for PostgreSQL with read replicas for reporting workloads. We project comfortable handling of 500,000+ accounts with this architecture, with load testing to validate at 2x projected peak.',
                'Security is embedded at every layer: network segmentation with Azure VNet and NSGs, encryption at rest (AES-256) and in transit (TLS 1.3), Azure Key Vault for secrets management. GDPR compliance through data minimization, purpose limitation, and automated data retention policies. Regular penetration testing and OWASP ZAP scans in CI pipeline. Privacy by design principles guide every architectural decision.',
                'Integration with BRP and BAG will be handled through a dedicated integration layer using Azure API Management as a proxy. We will implement StUF-BG and StUF-ZKN compliant message handlers, with fallback to REST wrappers where available. A staging environment with mock registries enables parallel development while awaiting production access credentials.',
                'Our core team consists of 8 professionals: Lead Architect (15 years, 4 government platforms), 2 Senior Backend Developers (both with municipal project experience), 2 Frontend Developers (React specialists, accessibility certified), 1 DevOps Engineer (Azure certified), 1 QA Lead (ISTQB certified), and 1 Scrum Master. All team members have Dutch government security clearance.',
                'Knowledge transfer is built into the project plan as a formal workstream. It includes: comprehensive technical documentation maintained throughout development, bi-weekly knowledge sharing sessions with municipal IT staff, a 3-month supported hypercare period post-launch, video-recorded training modules for all administrative functions, and a detailed runbook for operations.',
                'Key references: Municipality of Utrecht citizen portal (2023, 120K users), Province of Noord-Holland permit system (2024, 45K applications/year), Water Authority Delfland digital services platform (2022, won Dutch Digital Government Award). All projects delivered on time and within budget. References available upon request.',
                'We follow ISO 25010 quality model with specific metrics for each characteristic. Our QA process includes: code reviews (mandatory 2-reviewer policy), static analysis with SonarQube (zero critical/blocker policy), automated test suite with 85%+ code coverage target, performance testing with JMeter for SLA validation, and bi-weekly security scans.',
                'Accessibility compliance is verified through: automated axe-core scanning in CI pipeline, manual testing with screen readers (NVDA, JAWS, VoiceOver), keyboard-only navigation testing for all workflows, color contrast validation against WCAG 2.1 AA standards, user testing with participants who have various disabilities. Our UX lead holds IAAP CPWA certification.',
                'Automated testing strategy: Unit tests (xUnit/.NET, Jest/React) targeting 85%+ coverage. Integration tests for all API endpoints and database interactions. E2E tests using Playwright covering 100% of critical user journeys. Performance tests with JMeter for SLA validation. Security tests with OWASP ZAP integrated into CI. All tests must pass before deployment.',
                'Top 5 risks: 1) BRP/BAG integration delays (mitigation: mock services for parallel development), 2) Data migration complexity (mitigation: phased migration with rollback capability), 3) Scope creep (mitigation: strict change request process with impact analysis), 4) Performance under peak load (mitigation: early load testing and auto-scaling), 5) Key staff unavailability (mitigation: cross-trained pairs for all roles).',
                'We implement a formal change management process: change requests submitted through Jira with mandatory impact analysis covering timeline, budget, and technical implications. All changes require approval from both vendor PM and municipal product owner. Emergency changes have expedited review with 48-hour post-implementation documentation.',
                'Registry integration is our highest-risk dependency. Contingency: we develop the integration layer against mock services first, enabling 90% of development to proceed independently. If production access is delayed, we can launch with manual data entry as a temporary bridge. We maintain relationships with BRP/BAG integration specialists who can provide expedited support.',
                'Phase 1 (Months 1-2): Foundation — architecture setup, CI/CD, design system, authentication. Phase 2 (Months 3-4): Core modules — permit management, citizen portal, document processing. Phase 3 (Month 5): Integration — BRP/BAG connection, payment system, notifications. Phase 4 (Month 6): Testing & launch — UAT, performance testing, accessibility audit, go-live. Milestone reviews at each phase boundary.',
                'Critical dependencies: 1) Municipal IT team availability for integration testing (need 2 FTE from Month 3), 2) BRP/BAG access credentials (need by Month 3), 3) Design approval from communications department (need by Week 4), 4) Security audit scheduling (need to book Month 5), 5) Content migration from legacy system (need data export by Month 2).',
            ];

            Response::create([
                'participant_id' => $p1->id,
                'question_id' => $q->id,
                'answer_text' => $answers[$i] ?? 'Response pending further analysis.',
                'completeness_score' => rand(70, 100) / 100,
            ]);
        }

        // Responses from Marcus (most questions answered)
        $marcusAnswers = [
            'From a security perspective, we recommend a zero-trust architecture with Azure as the primary cloud provider. Every service-to-service communication must be authenticated and authorized, regardless of network position. Key technologies: Azure AD B2C for citizen identity, Azure Sentinel for SIEM, Azure Policy for governance automation. All infrastructure defined as code using Terraform.',
            'Scaling must account for burst traffic during permit deadline periods. We recommend Azure Front Door for global load balancing, with Redis Cache for session state and frequently accessed data. Database scaling through read replicas and connection pooling. Cost optimization through reserved instances for baseline load with spot instances for burst capacity.',
            'Our security approach follows the NIST Cybersecurity Framework and Dutch BIO baseline. Specific measures: multi-factor authentication for all administrative access, automated vulnerability scanning, network microsegmentation, API rate limiting, DDoS protection via Azure Front Door, data classification and handling procedures aligned with Dutch government security classification standards.',
            'BRP integration requires careful attention to data classification. All personal data must be encrypted end-to-end with AES-256. Access to registry data will be logged and auditable. We propose a dedicated service account with principle of least privilege. Integration testing must occur in an isolated environment with synthetic data only.',
            'Our security team includes a certified CISSP (20 years experience), two OSCP-certified penetration testers, and a privacy specialist with CIPP/E certification. All team members undergo annual security awareness training and have current Dutch VGB (Verklaring van Geen Bezwaar) declarations.',
            'Knowledge transfer for security operations includes: detailed threat model documentation, incident response playbooks, key rotation procedures, and quarterly security posture reviews for the first year post-deployment.',
            'We secured the Province of Zuid-Holland digital identity platform (2024, 0 security incidents), Den Haag Smart City secure IoT gateway (2023), and the Dutch National Archives digital preservation system (2022). Security audit results available under NDA.',
            'Security-specific QA includes: SAST with Checkmarx in every build, DAST with OWASP ZAP nightly, dependency scanning with Snyk for supply chain security, and quarterly external penetration testing by an independent security firm.',
            'Accessibility must not compromise security. We ensure CAPTCHA alternatives are accessible, authentication flows are keyboard-navigable, and security error messages are descriptive without revealing attack vectors.',
            'Security testing is embedded in CI/CD: Snyk for dependency vulnerabilities (fails build on critical), SonarQube security hotspots (mandatory review), OWASP ZAP baseline scan (zero high-risk findings policy), and custom security test suite for authorization boundary testing.',
        ];

        foreach (array_slice($questions, 0, 10) as $i => $q) {
            Response::create([
                'participant_id' => $p2->id,
                'question_id' => $q->id,
                'answer_text' => $marcusAnswers[$i],
                'completeness_score' => rand(75, 100) / 100,
            ]);
        }

        // Partial responses from Lena
        $lenaAnswers = [
            'From a UX perspective, the frontend should use React with a design system built on Radix UI primitives for maximum accessibility. Server-side rendering with Next.js ensures fast initial load times for citizens on slower connections. Progressive enhancement means the core workflows function without JavaScript enabled.',
            'The citizen experience must remain consistent regardless of scale. We implement skeleton loading states, optimistic UI updates, and offline capability for form data. Performance budgets: LCP under 2.5s, FID under 100ms, CLS under 0.1.',
        ];

        foreach (array_slice($questions, 0, 2) as $i => $q) {
            Response::create([
                'participant_id' => $p3->id,
                'question_id' => $q->id,
                'answer_text' => $lenaAnswers[$i],
                'completeness_score' => rand(60, 85) / 100,
            ]);
        }

        // Follow-ups on Lena's responses
        FollowUp::create([
            'response_id' => Response::where('participant_id', $p3->id)->first()->id,
            'role' => 'ai',
            'message' => 'Thank you for the frontend technology recommendation. Could you elaborate on how you would handle the design system governance — who maintains it, how are new components reviewed, and how do you ensure consistency across the 12 modules?',
            'sequence' => 1,
        ]);

        // Analyses for Tender 1
        Analysis::create([
            'tender_id' => $t1->id,
            'type' => 'consensus',
            'title' => 'Consensus Points Analysis',
            'content' => [
                'summary' => 'Strong alignment across participants on core architectural decisions and security requirements.',
                'points' => [
                    ['area' => 'Cloud Provider', 'agreement_level' => 95, 'detail' => 'All participants recommend Azure, citing EU government certifications and Dutch data center availability.'],
                    ['area' => 'Security Framework', 'agreement_level' => 90, 'detail' => 'Unanimous agreement on zero-trust architecture and Dutch BIO baseline compliance.'],
                    ['area' => 'Testing Standards', 'agreement_level' => 85, 'detail' => 'All participants specify automated testing with 85%+ code coverage targets.'],
                ],
            ],
            'confidence' => 0.88,
        ]);

        Analysis::create([
            'tender_id' => $t1->id,
            'type' => 'gaps',
            'title' => 'Missing & Weak Areas Assessment',
            'content' => [
                'summary' => 'Several areas need additional expert input before the quality plan can be finalized.',
                'gaps' => [
                    ['area' => 'Disaster Recovery', 'severity' => 'critical', 'detail' => 'No specific RPO/RTO targets defined. Need architect input on failover architecture.'],
                    ['area' => 'Content Migration Strategy', 'severity' => 'high', 'detail' => 'Legacy system data mapping not yet addressed by any participant.'],
                    ['area' => 'User Training Plan', 'severity' => 'medium', 'detail' => 'Knowledge transfer mentioned but no detailed citizen onboarding strategy.'],
                ],
            ],
            'confidence' => 0.82,
        ]);

        // Tender 2: Draft
        $t2 = Tender::create([
            'user_id' => $user->id,
            'name' => 'Smart Building Energy Management System',
            'description' => 'IoT-integrated energy monitoring and optimization platform for commercial buildings. Real-time dashboards, predictive maintenance, and automated energy trading.',
            'objectives' => "Reduce building energy costs by 25%\nReal-time monitoring of all energy systems\nPredictive maintenance alerts\nAutomated energy trading with grid operators",
            'deadline' => now()->addDays(30),
            'status' => 'draft',
        ]);

        Criterion::create(['tender_id' => $t2->id, 'name' => 'IoT Integration', 'description' => 'Sensor connectivity and data ingestion architecture', 'weight' => 25, 'sort_order' => 1]);
        Criterion::create(['tender_id' => $t2->id, 'name' => 'Data Analytics', 'description' => 'Real-time processing and predictive modeling', 'weight' => 25, 'sort_order' => 2]);
        Criterion::create(['tender_id' => $t2->id, 'name' => 'User Interface', 'description' => 'Dashboard design and mobile access', 'weight' => 20, 'sort_order' => 3]);
        Criterion::create(['tender_id' => $t2->id, 'name' => 'Scalability', 'description' => 'Multi-building deployment capability', 'weight' => 15, 'sort_order' => 4]);
        Criterion::create(['tender_id' => $t2->id, 'name' => 'Compliance', 'description' => 'Energy regulation and data privacy compliance', 'weight' => 15, 'sort_order' => 5]);

        // Tender 3: Completed
        Tender::create([
            'user_id' => $user->id,
            'name' => 'National Healthcare Data Exchange Platform',
            'description' => 'Interoperability platform for exchanging patient records between hospitals, GPs, and pharmacies using HL7 FHIR standards.',
            'status' => 'completed',
            'deadline' => now()->subDays(10),
        ]);

        // Tender 4: Reviewing
        $t4 = Tender::create([
            'user_id' => $user->id,
            'name' => 'Public Transport Real-Time Information System',
            'description' => 'Real-time passenger information system integrating multiple transport operators. Digital signage, mobile app, and API for third-party developers.',
            'status' => 'reviewing',
            'deadline' => now()->addDays(5),
        ]);

        Criterion::create(['tender_id' => $t4->id, 'name' => 'Real-Time Data Processing', 'weight' => 30, 'sort_order' => 1]);
        Criterion::create(['tender_id' => $t4->id, 'name' => 'Multi-Operator Integration', 'weight' => 25, 'sort_order' => 2]);
        Criterion::create(['tender_id' => $t4->id, 'name' => 'Accessibility', 'weight' => 25, 'sort_order' => 3]);
        Criterion::create(['tender_id' => $t4->id, 'name' => 'Maintenance & Support', 'weight' => 20, 'sort_order' => 4]);

        $p5 = Participant::create(['tender_id' => $t4->id, 'name' => 'Jan de Groot', 'email' => 'jan@transport-tech.nl', 'role' => 'Systems Architect', 'token' => 'demo_token_jan_0123456789012345678901234567', 'status' => 'submitted', 'invited_at' => now()->subDays(14), 'submitted_at' => now()->subDays(3)]);
        $p6 = Participant::create(['tender_id' => $t4->id, 'name' => 'Anna Pieters', 'email' => 'anna@mobilitynl.com', 'role' => 'Data Engineer', 'token' => 'demo_token_anna_01234567890123456789012345', 'status' => 'submitted', 'invited_at' => now()->subDays(14), 'submitted_at' => now()->subDays(5)]);
    }
}
