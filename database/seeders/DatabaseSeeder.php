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
            'password' => Hash::make('2WBE8QrawPqMtshj'),
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

        // Tender 3: Completed (fully populated — criteria, participants, responses, analyses)
        $t3 = Tender::create([
            'user_id' => $user->id,
            'name' => 'National Healthcare Data Exchange Platform',
            'description' => 'Interoperability platform for exchanging patient records between hospitals, GPs, and pharmacies using HL7 FHIR standards.',
            'objectives' => "Enable real-time patient data exchange between 400+ healthcare providers\nFull HL7 FHIR R4 compliance\nMeet NEN 7510 and GDPR healthcare data requirements\nReduce duplicate diagnostic tests by 40%",
            'focus_themes' => "Interoperability, Patient Privacy, Clinical Workflow Integration, Reliability",
            'context_notes' => 'Replaces legacy point-to-point HL7v2 integrations. Must coexist with existing systems during 18-month migration period. Budget approved by Ministry of Health.',
            'status' => 'completed',
            'deadline' => now()->subDays(10),
        ]);

        // Criteria for Tender 3
        $t3c1 = Criterion::create(['tender_id' => $t3->id, 'name' => 'Interoperability Architecture', 'description' => 'FHIR implementation quality, API design, and standards compliance', 'weight' => 30, 'sort_order' => 1]);
        $t3c2 = Criterion::create(['tender_id' => $t3->id, 'name' => 'Privacy & Security', 'description' => 'Patient consent management, data encryption, and NEN 7510 compliance', 'weight' => 25, 'sort_order' => 2]);
        $t3c3 = Criterion::create(['tender_id' => $t3->id, 'name' => 'Clinical Workflow Integration', 'description' => 'Minimal disruption to existing clinical processes and EHR systems', 'weight' => 25, 'sort_order' => 3]);
        $t3c4 = Criterion::create(['tender_id' => $t3->id, 'name' => 'Migration Strategy', 'description' => 'Transition plan from legacy HL7v2 to FHIR with zero data loss', 'weight' => 20, 'sort_order' => 4]);

        // Questions for Tender 3
        $t3q = [];
        $t3q[] = Question::create(['criterion_id' => $t3c1->id, 'question_text' => 'Detail your approach to implementing HL7 FHIR R4 resources and how you will handle custom Dutch extensions (nl-core profiles).', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t3q[] = Question::create(['criterion_id' => $t3c1->id, 'question_text' => 'How will your architecture handle message routing between 400+ providers with varying technical maturity?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 2]);
        $t3q[] = Question::create(['criterion_id' => $t3c1->id, 'question_text' => 'Describe your API versioning strategy to support both legacy HL7v2 and FHIR endpoints during the transition period.', 'priority' => 'high', 'source' => 'manual', 'sort_order' => 3]);
        $t3q[] = Question::create(['criterion_id' => $t3c2->id, 'question_text' => 'How does your system implement patient consent management in compliance with GDPR Article 9 (health data)?', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t3q[] = Question::create(['criterion_id' => $t3c2->id, 'question_text' => 'Describe your approach to NEN 7510 certification and ongoing compliance monitoring.', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 2]);
        $t3q[] = Question::create(['criterion_id' => $t3c3->id, 'question_text' => 'How will you integrate with major Dutch EHR systems (HiX, Epic, Chipsoft) without disrupting clinical workflows?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t3q[] = Question::create(['criterion_id' => $t3c3->id, 'question_text' => 'What is your strategy for handling asynchronous data exchange when a provider system is temporarily offline?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 2]);
        $t3q[] = Question::create(['criterion_id' => $t3c4->id, 'question_text' => 'Provide a phased migration plan from legacy HL7v2 interfaces to FHIR, including rollback procedures.', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t3q[] = Question::create(['criterion_id' => $t3c4->id, 'question_text' => 'How will you ensure zero data loss during the transition, especially for in-flight patient records?', 'priority' => 'high', 'source' => 'manual', 'sort_order' => 2]);

        // Participants for Tender 3 (all submitted — completed tender)
        $t3p1 = Participant::create(['tender_id' => $t3->id, 'name' => 'Dr. Willem Hendriks', 'email' => 'w.hendriks@healthit.nl', 'role' => 'Health IT Architect', 'token' => 'demo_token_willem_012345678901234567890123', 'status' => 'submitted', 'invited_at' => now()->subDays(45), 'last_active_at' => now()->subDays(15), 'submitted_at' => now()->subDays(15)]);
        $t3p2 = Participant::create(['tender_id' => $t3->id, 'name' => 'Mirjam van Dijk', 'email' => 'mirjam@nictiz-consult.nl', 'role' => 'FHIR Standards Specialist', 'token' => 'demo_token_mirjam_01234567890123456789012', 'status' => 'submitted', 'invited_at' => now()->subDays(45), 'last_active_at' => now()->subDays(12), 'submitted_at' => now()->subDays(12)]);
        $t3p3 = Participant::create(['tender_id' => $t3->id, 'name' => 'Pieter Koster', 'email' => 'p.koster@hospital-systems.eu', 'role' => 'Clinical Systems Integrator', 'token' => 'demo_token_pieter_0123456789012345678901', 'status' => 'submitted', 'invited_at' => now()->subDays(45), 'last_active_at' => now()->subDays(14), 'submitted_at' => now()->subDays(14)]);

        // Responses from Willem (all questions)
        $willemAnswers = [
            'We implement HL7 FHIR R4 with full support for nl-core profiles maintained by Nictiz. Our resource server supports Patient, Observation, Condition, MedicationRequest, and AllergyIntolerance as core resources. Custom extensions are managed through a dedicated profile registry with automated conformance validation. All resources pass the official HL7 FHIR validator before storage.',
            'Our routing architecture uses an intelligent message broker (Apache Kafka) with provider-specific adapters. Each connected provider has a capability statement registered in our directory service. For providers with limited technical capability, we offer a lightweight REST gateway that handles FHIR translation. The broker auto-selects the optimal transport protocol per provider.',
            'API versioning follows the FHIR specification model: major versions in the URL path (/fhir/r4/), minor versions via HTTP headers. During the transition, our dual-stack gateway accepts both HL7v2 (MLLP) and FHIR (REST) and performs real-time translation between formats using a mapping engine certified against the Nictiz conversion specifications.',
            'Patient consent is implemented as a FHIR Consent resource linked to each patient record. Our consent service enforces granular permissions: per-provider, per-data-category, and time-bounded access. Consent changes propagate within 30 seconds via event-driven architecture. Emergency override ("break the glass") is logged, audited, and triggers automatic notification to the patient.',
            'NEN 7510 compliance is built into our infrastructure from the ground up. We maintain continuous compliance through automated controls: encrypted storage (AES-256), encrypted transport (TLS 1.3), role-based access with MFA, comprehensive audit logging with 7-year retention. Annual external audits by a NEN-accredited body. Our platform has held NEN 7510 certification since 2023.',
            'We have certified adapters for HiX (ChipSoft), Epic (via FHIR R4 native), and SAP Health. For HiX, we use their HL7v2 interface during transition, with FHIR adapter planned for their 2024 release. Integration is asynchronous by default — clinicians see a real-time status indicator but their workflow is never blocked by external data fetches. Data appears within 2 seconds of availability.',
            'Offline handling uses a store-and-forward pattern. When a provider system is unavailable, messages queue in our persistent message store (Kafka with 7-day retention). Upon reconnection, messages replay in order with idempotency keys preventing duplicates. Critical alerts (allergies, medication interactions) use a priority queue with SMS fallback to the treating physician.',
            'Migration follows four phases over 18 months: Phase 1 (M1-3) — parallel running with data comparison audits. Phase 2 (M4-9) — progressive traffic shifting, 10% → 50% FHIR. Phase 3 (M10-15) — full FHIR with HL7v2 fallback. Phase 4 (M16-18) — legacy decommission. Each phase has go/no-go criteria and automated rollback triggers. We have successfully executed this pattern for Province of Gelderland.',
            'Zero data loss is guaranteed through dual-write during transition: every message is persisted in both legacy and FHIR formats until Phase 4 completion. Reconciliation jobs run hourly comparing both stores. Any discrepancy triggers an alert and automatic pause of the migration for the affected provider. In-flight records use transaction boundaries with compensation logic for partial failures.',
        ];
        foreach ($t3q as $i => $q) {
            Response::create(['participant_id' => $t3p1->id, 'question_id' => $q->id, 'answer_text' => $willemAnswers[$i], 'completeness_score' => rand(80, 100) / 100]);
        }

        // Responses from Mirjam (all questions)
        $mirjamAnswers = [
            'As a Nictiz-certified FHIR implementer, I recommend strict adherence to the nl-core 2024.1 profile set. Key resources: nl-core-Patient, nl-core-MedicationAgreement, nl-core-LaboratoryTestResult. Our validation engine checks both structural conformance and Dutch business rules (BSN validation, UZI certificate verification). We contribute upstream fixes to the Nictiz profile repository.',
            'Provider routing should leverage the FHIR CapabilityStatement as a living directory. Each provider registers what resources they can send and receive. Our smart routing layer matches incoming requests against capabilities and selects the optimal path. For low-maturity providers, we provide a managed SaaS gateway — they upload CSV or PDF, we convert to FHIR.',
            'The dual-stack approach is essential. Our translation engine handles 47 HL7v2 message types mapped to FHIR equivalents. Version negotiation uses content-type headers (application/fhir+json; fhirVersion=4.0). We maintain a compatibility matrix updated quarterly. The legacy gateway will run for a minimum of 24 months beyond the last provider migration.',
            'Consent management must follow the Mitz (Mijn Toestemming) national consent registry integration requirements. Our system syncs with Mitz every 5 minutes and enforces consent locally for sub-second authorization decisions. We support the Dutch "opt-in" model per data category. Emergency access follows the NHG (Nederlands Huisartsen Genootschap) guidelines with mandatory post-hoc justification.',
            'NEN 7510 is necessary but not sufficient. We also implement NEN 7512 (data exchange), NEN 7513 (logging), and ISO 27001. Our compliance dashboard provides real-time visibility into all 150+ controls. Automated evidence collection reduces audit preparation from weeks to hours. We offer compliance-as-a-service: if a connected provider needs NEN 7510 help, our team assists.',
            'Clinical workflow integration requires a human-centered design approach. We embedded UX researchers in 3 hospitals for 6 months to map actual clinical workflows. Result: data exchange happens in the background — clinicians see a unified patient timeline regardless of data source. We use SMART on FHIR for EHR-embedded applications that feel native to each system.',
            'Our store-and-forward implementation uses exactly-once delivery semantics with Kafka transactions. For critical patient safety data (allergies, current medications), we maintain a replicated cache at each major hospital that updates independently via gossip protocol. Even during a complete network partition, safety-critical data remains available from the last known state.',
            'We recommend the "strangler fig" pattern from our successful Erasmus MC migration. New connections use FHIR from day one. Existing HL7v2 connections are migrated in priority order (busiest routes first for maximum impact). Each migration includes a 2-week shadow period where both protocols run simultaneously and outputs are compared. Success criteria: 99.99% message parity.',
            'Data integrity is verified through cryptographic checksums on every record at source and destination. Our reconciliation engine (runs every 15 minutes) compares source and destination states using Merkle trees for efficient difference detection. Any mismatch immediately halts automated processing and triggers manual review. We have never lost a patient record in 8 years of operation.',
        ];
        foreach ($t3q as $i => $q) {
            Response::create(['participant_id' => $t3p2->id, 'question_id' => $q->id, 'answer_text' => $mirjamAnswers[$i], 'completeness_score' => rand(82, 100) / 100]);
        }

        // Responses from Pieter (most questions)
        $pieterAnswers = [
            'From a clinical integration perspective, FHIR R4 with nl-core profiles is the right foundation. However, I emphasize the importance of the Observation resource for lab results — this is where most data exchange volume occurs. We need to support both structured (LOINC-coded) and narrative lab results, as many smaller labs still produce PDF reports that need OCR and NLP processing.',
            'The 400+ provider network requires a tiered approach. Tier 1 (university hospitals, 8 providers): native FHIR with dedicated high-throughput connections. Tier 2 (regional hospitals, ~80 providers): FHIR via standard REST APIs. Tier 3 (GPs and pharmacies, 300+ providers): lightweight gateway with batch processing option for smaller practices still on daily file exports.',
            'During transition, clinical users must never see a degraded experience. Our middleware presents a unified FHIR interface upstream while handling protocol translation downstream. Clinicians interact with one API regardless of whether the source is HL7v2 or FHIR. Version differences are abstracted away — the clinical user should not need to know or care about the plumbing.',
            'Consent in practice is more nuanced than the technical spec suggests. We implement progressive consent: basic demographics shared by default (per Dutch law for continuity of care), detailed records require explicit opt-in, psychiatric and genetic data require separate enhanced consent. The UI presents consent choices in plain language — patients approve "sharing your medication list with your GP" not "FHIR MedicationRequest READ access."',
            'Security must be practical for clinical environments. Badge-based SSO with context-aware authentication — if a nurse is at her usual workstation during her shift, authentication is streamlined. If the same credentials appear from an unusual location, step-up authentication triggers. We integrate with UZI (Unieke Zorgverlener Identificatie) smart cards for physician authentication.',
            'EHR integration is my specialty. For HiX environments (65% of Dutch hospitals), we have a certified connector that took 2 years to build and is deployed at 14 sites. For Epic, we use their native FHIR server with a thin adaptation layer for nl-core profiles. Key principle: zero new windows. Everything surfaces within the EHR the clinician already uses.',
            'Offline resilience for clinical safety: every hospital node maintains a local FHIR server with the patient safety subset (allergies, active medications, critical conditions). This syncs continuously but operates independently during outages. We tested this during the 2024 Maastricht UMC network incident — their clinical staff had uninterrupted access to critical patient data.',
        ];
        foreach (array_slice($t3q, 0, 7) as $i => $q) {
            Response::create(['participant_id' => $t3p3->id, 'question_id' => $q->id, 'answer_text' => $pieterAnswers[$i], 'completeness_score' => rand(75, 95) / 100]);
        }

        // Analyses for Tender 3 (completed tender should have all analysis types)
        Analysis::create([
            'tender_id' => $t3->id,
            'type' => 'consensus',
            'title' => 'Consensus Points Analysis',
            'content' => [
                'summary' => 'Exceptionally strong alignment across all three healthcare IT specialists on core architecture and compliance requirements.',
                'points' => [
                    ['area' => 'FHIR R4 with nl-core Profiles', 'agreement_level' => 98, 'detail' => 'Unanimous agreement on HL7 FHIR R4 as the target standard with full nl-core profile support. All participants reference Nictiz specifications.'],
                    ['area' => 'NEN 7510 Compliance', 'agreement_level' => 95, 'detail' => 'All participants treat NEN 7510 as baseline, with two advocating for additional NEN 7512/7513 and ISO 27001.'],
                    ['area' => 'Patient Consent Model', 'agreement_level' => 88, 'detail' => 'Agreement on opt-in consent aligned with Mitz integration, though granularity approaches differ slightly.'],
                    ['area' => 'Dual-Stack Transition', 'agreement_level' => 92, 'detail' => 'Strong consensus on running HL7v2 and FHIR in parallel with automated translation during migration.'],
                ],
            ],
            'confidence' => 0.94,
        ]);

        Analysis::create([
            'tender_id' => $t3->id,
            'type' => 'conflicts',
            'title' => 'Conflicting Viewpoints Report',
            'content' => [
                'summary' => 'Minor conflicts identified, primarily around implementation approach rather than fundamental architecture.',
                'conflicts' => [
                    ['topic' => 'Message Broker Technology', 'positions' => ['Apache Kafka for event streaming (Willem)', 'Kafka with gossip protocol overlay for safety data (Mirjam)'], 'severity' => 'low'],
                    ['topic' => 'Low-Maturity Provider Support', 'positions' => ['Lightweight REST gateway (Willem)', 'SaaS gateway with CSV/PDF upload (Mirjam)', 'Tiered approach with batch processing (Pieter)'], 'severity' => 'medium'],
                    ['topic' => 'Migration Pattern', 'positions' => ['Four-phase progressive traffic shifting (Willem)', 'Strangler fig with shadow periods (Mirjam)'], 'severity' => 'medium'],
                ],
            ],
            'confidence' => 0.86,
        ]);

        Analysis::create([
            'tender_id' => $t3->id,
            'type' => 'themes',
            'title' => 'Recurring Themes Identification',
            'content' => [
                'summary' => 'Four dominant themes emerge from the healthcare data exchange responses.',
                'themes' => [
                    ['theme' => 'Patient Safety as Non-Negotiable', 'frequency' => 18, 'participants_mentioning' => 3, 'sample_quotes' => ['Safety-critical data remains available even during complete network partition']],
                    ['theme' => 'Clinician Workflow Preservation', 'frequency' => 12, 'participants_mentioning' => 3, 'sample_quotes' => ['Zero new windows — everything surfaces within the EHR the clinician already uses']],
                    ['theme' => 'Standards-First Approach', 'frequency' => 15, 'participants_mentioning' => 3, 'sample_quotes' => ['Strict adherence to nl-core 2024.1 profile set']],
                    ['theme' => 'Graceful Degradation', 'frequency' => 9, 'participants_mentioning' => 3, 'sample_quotes' => ['Even during outages, clinical staff had uninterrupted access to critical patient data']],
                ],
            ],
            'confidence' => 0.91,
        ]);

        Analysis::create([
            'tender_id' => $t3->id,
            'type' => 'risks',
            'title' => 'Risk Signal Detection',
            'content' => [
                'summary' => 'Healthcare data exchange carries inherent high-stakes risks around patient safety and regulatory compliance.',
                'risks' => [
                    ['risk' => 'Legacy System Decommission Resistance', 'probability' => 'high', 'impact' => 'high', 'signal' => 'Multiple participants note that some providers may resist migration from working HL7v2 systems.'],
                    ['risk' => 'Consent Complexity at Scale', 'probability' => 'medium', 'impact' => 'critical', 'signal' => 'Granular consent across 400+ providers with varying consent UIs could lead to patient confusion and data access failures.'],
                    ['risk' => 'EHR Vendor Lock-In', 'probability' => 'medium', 'impact' => 'high', 'signal' => 'HiX connector required 2 years to build — dependency on vendor cooperation for FHIR readiness.'],
                    ['risk' => 'Regulatory Change During Migration', 'probability' => 'low', 'impact' => 'high', 'signal' => 'Dutch healthcare data regulations are evolving — European Health Data Space (EHDS) requirements may shift during 18-month timeline.'],
                ],
            ],
            'confidence' => 0.87,
        ]);

        Analysis::create([
            'tender_id' => $t3->id,
            'type' => 'insights',
            'title' => 'Notable Insights Summary',
            'content' => [
                'summary' => 'Deep analysis reveals several non-obvious findings from the expert panel responses.',
                'insights' => [
                    ['insight' => 'All three participants independently converged on maintaining local patient safety caches at each hospital — this emergent pattern suggests it should be a core architectural requirement, not an optional feature.', 'relevance' => 'high'],
                    ['insight' => 'The gap between technical FHIR compliance and clinical usability is the real challenge. Pieter\'s observation that "patients approve sharing medication list, not FHIR MedicationRequest READ access" highlights a critical UX design principle for the consent system.', 'relevance' => 'high'],
                    ['insight' => 'The tiered provider approach (Pieter) combined with the managed SaaS gateway (Mirjam) and the capability-based routing (Willem) can be synthesized into a single elegant solution — an intelligent onboarding system that assesses provider maturity and auto-provisions the appropriate integration tier.', 'relevance' => 'high'],
                ],
            ],
            'confidence' => 0.89,
        ]);

        Analysis::create([
            'tender_id' => $t3->id,
            'type' => 'gaps',
            'title' => 'Missing & Weak Areas Assessment',
            'content' => [
                'summary' => 'Despite comprehensive responses, several areas need additional definition before project closure.',
                'gaps' => [
                    ['area' => 'Cost Model for Small Providers', 'severity' => 'high', 'detail' => 'No participant addressed the per-provider onboarding cost for small GP practices and pharmacies (estimated 300+ connections).'],
                    ['area' => 'Performance Benchmarks', 'severity' => 'medium', 'detail' => 'Real-time exchange targets stated but no load testing methodology for simulating 400+ concurrent provider connections.'],
                    ['area' => 'Patient-Facing Data Access', 'severity' => 'medium', 'detail' => 'Focus is on provider-to-provider exchange; patient portal for viewing their own shared data not addressed.'],
                ],
            ],
            'confidence' => 0.83,
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

        // Questions for Tender 4
        $t4c1 = Criterion::where('tender_id', $t4->id)->where('sort_order', 1)->first();
        $t4c2 = Criterion::where('tender_id', $t4->id)->where('sort_order', 2)->first();
        $t4c3 = Criterion::where('tender_id', $t4->id)->where('sort_order', 3)->first();
        $t4c4 = Criterion::where('tender_id', $t4->id)->where('sort_order', 4)->first();

        $t4q = [];
        $t4q[] = Question::create(['criterion_id' => $t4c1->id, 'question_text' => 'How will your system process real-time vehicle position data from multiple operators with varying data formats (GTFS-RT, SIRI, proprietary)?', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t4q[] = Question::create(['criterion_id' => $t4c1->id, 'question_text' => 'What is your approach to achieving sub-second latency for arrival predictions displayed on platform signage?', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 2]);
        $t4q[] = Question::create(['criterion_id' => $t4c2->id, 'question_text' => 'Describe your strategy for onboarding new transport operators without disrupting existing data feeds.', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t4q[] = Question::create(['criterion_id' => $t4c2->id, 'question_text' => 'How will you handle data quality issues from operators (missing GPS signals, stale feeds, incorrect route assignments)?', 'priority' => 'high', 'source' => 'manual', 'sort_order' => 2]);
        $t4q[] = Question::create(['criterion_id' => $t4c3->id, 'question_text' => 'Detail your approach to WCAG 2.1 AA compliance for digital signage, mobile apps, and the public API documentation.', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t4q[] = Question::create(['criterion_id' => $t4c3->id, 'question_text' => 'How will multi-language support work, including real-time service disruption announcements in Dutch, English, German, and French?', 'priority' => 'normal', 'source' => 'ai_generated', 'sort_order' => 2]);
        $t4q[] = Question::create(['criterion_id' => $t4c4->id, 'question_text' => 'What is your proposed SLA for system uptime, and how do you handle failover during peak commuter hours?', 'priority' => 'critical', 'source' => 'ai_generated', 'sort_order' => 1]);
        $t4q[] = Question::create(['criterion_id' => $t4c4->id, 'question_text' => 'Describe your approach to software updates and maintenance windows without disrupting live passenger information.', 'priority' => 'high', 'source' => 'ai_generated', 'sort_order' => 2]);

        // Participants for Tender 4
        $p5 = Participant::create(['tender_id' => $t4->id, 'name' => 'Jan de Groot', 'email' => 'jan@transport-tech.nl', 'role' => 'Systems Architect', 'token' => 'demo_token_jan_0123456789012345678901234567', 'status' => 'submitted', 'invited_at' => now()->subDays(14), 'last_active_at' => now()->subDays(3), 'submitted_at' => now()->subDays(3)]);
        $p6 = Participant::create(['tender_id' => $t4->id, 'name' => 'Anna Pieters', 'email' => 'anna@mobilitynl.com', 'role' => 'Data Engineer', 'token' => 'demo_token_anna_01234567890123456789012345', 'status' => 'submitted', 'invited_at' => now()->subDays(14), 'last_active_at' => now()->subDays(5), 'submitted_at' => now()->subDays(5)]);

        // Responses from Jan (all questions)
        $janAnswers = [
            'Our ingestion layer uses Apache Flink for stream processing with protocol-specific adapters. GTFS-RT feeds are consumed via HTTP polling (configurable 1-30s intervals). SIRI feeds use publish/subscribe over AMQP. Proprietary formats are handled by custom adapters in a plugin architecture — adding a new operator format requires only a new adapter module, no core changes. All data normalizes into our internal Unified Transit Event schema.',
            'Sub-second signage updates require edge computing. Each station has a local processing node that receives pre-computed predictions from our central engine and handles display rendering locally. The central prediction engine uses a Kalman filter combining GPS positions, historical travel times, and real-time conditions. Network latency is eliminated from the critical path — the edge node always has a current prediction ready.',
            'Operator onboarding follows a standardized 3-week process: Week 1 — data feed assessment and adapter configuration. Week 2 — parallel shadow running where new feeds are processed but not displayed. Week 3 — graduated visibility starting with internal dashboards, then staff displays, then public displays. Each stage has quality gates. Existing feeds are completely isolated — new operators cannot degrade existing service.',
            'Data quality is our biggest operational challenge. Our quality engine scores every incoming data point on freshness, spatial consistency, and route conformity. Stale GPS (>60s) triggers interpolation from schedule + historical patterns. Inconsistent positions (vehicle jumping) are smoothed using a confidence-weighted moving average. Operators receive automated daily quality scorecards — this has improved average data quality by 35% across our existing deployments.',
            'Signage accessibility: high-contrast displays meeting EN 12966 for electronic signs, audio announcements via text-to-speech (Nuance Vocalizer, Dutch/English), tactile wayfinding integration for the visually impaired. Mobile app: VoiceOver/TalkBack optimized, dynamic text scaling, one-handed operation mode. API docs: WCAG 2.1 AA compliant portal with screen-reader-tested examples. All certified by Stichting Accessibility.',
            'Multi-language is handled through a real-time translation pipeline. Structured data (routes, stops, times) uses a pre-translated reference database. Dynamic disruption messages use a template system with parameterized translations: "Train [line] from [origin] to [destination] is delayed by [minutes] minutes." Free-text announcements from operators go through a human translation queue with 5-minute SLA for critical alerts.',
            'We propose 99.95% uptime SLA (22 minutes downtime/month maximum). Architecture: active-active across two data centers with automatic DNS failover. During peak hours (6-9 AM, 4-7 PM), additional compute capacity auto-scales. If our central engine fails, edge nodes at stations continue displaying the last prediction with a "based on schedule" indicator. Passenger impact of any single component failure: zero visible degradation.',
            'Zero-downtime deployments using blue-green with canary releases. New versions deploy to the blue environment, receive 5% of traffic for 30 minutes, then progressively shift to 100% over 2 hours. Rollback is instant (DNS switch). Infrastructure maintenance (OS patches, hardware) uses rolling updates across our HA cluster — always N-1 capacity available. We have not had a maintenance-related outage in 4 years of operation.',
        ];
        foreach ($t4q as $i => $q) {
            Response::create(['participant_id' => $p5->id, 'question_id' => $q->id, 'answer_text' => $janAnswers[$i], 'completeness_score' => rand(78, 100) / 100]);
        }

        // Responses from Anna (all questions)
        $annaAnswers = [
            'Data ingestion must be format-agnostic at the API boundary. I propose a schema-on-read approach using Apache Kafka as the universal transport layer. Each operator pushes to a dedicated Kafka topic. Format-specific deserializers (GTFS-RT protobuf, SIRI XML, custom JSON) run as independent consumers that output standardized events. This decouples operator data formats from our processing pipeline entirely.',
            'Prediction accuracy matters more than raw latency. My approach combines three models: a physics-based model (speed + distance), a statistical model (historical patterns by time-of-day, day-of-week, weather), and a machine learning model (gradient-boosted trees trained on 2 years of data). An ensemble layer selects the best prediction per segment. Average prediction error: 23 seconds for arrivals within 10 minutes.',
            'New operator integration should be self-service where possible. We provide a developer portal with sandbox environments where operators can test their data feeds against our validation suite. The portal auto-detects feed format, suggests the appropriate adapter, and runs a 24-hour quality assessment. Only after the automated assessment passes does the feed enter our manual review queue for production approval.',
            'I built a data quality framework at NS (Dutch Railways) that we can adapt here. Core principle: never display uncertain data as certain. When GPS quality degrades, we shift from "arriving in 3 minutes" to "expected 3-5 minutes" with a visual uncertainty indicator. When a feed goes completely dark, we fall back to schedule with clear labeling. Passengers trust honest uncertainty more than false precision.',
            'Accessibility is a data problem as much as a UX problem. Our data model includes accessibility attributes at every level: stop accessibility (wheelchair, escalator status), vehicle accessibility (low-floor, audio announcements), and route accessibility (step-free journey planning). The mobile app offers a dedicated accessibility mode that pre-filters to accessible options and provides vibration alerts for stop approach.',
            'We store all translatable content in a structured i18n database. For Dutch and English, all content is human-translated and pre-loaded. German and French use a hybrid approach: core vocabulary (routes, stops, standard phrases) is human-translated, while dynamic content uses DeepL with human review within 15 minutes. Disruption severity determines the pipeline: critical → human translator on-call, informational → machine translation.',
            'Our SLA framework: 99.95% for data ingestion (we must keep receiving), 99.9% for predictions (we can briefly show schedule-based data), 99.99% for signage display (edge nodes must always show something). Failover: each component has independent health checks and auto-replacement. We use chaos engineering (monthly failure injection) to validate resilience. Last measured: recovered from simulated data center loss in 47 seconds.',
            'Maintenance strategy: infrastructure and application layers are fully containerized (Kubernetes). Updates deploy as new pod versions with health checks — if the new version fails health checks, it auto-rolls back. We use GitOps (ArgoCD) so every change is version-controlled and auditable. Database migrations use expand-contract pattern: backward-compatible schema changes first, then application update, then cleanup. No maintenance windows required.',
        ];
        foreach ($t4q as $i => $q) {
            Response::create(['participant_id' => $p6->id, 'question_id' => $q->id, 'answer_text' => $annaAnswers[$i], 'completeness_score' => rand(80, 100) / 100]);
        }

        // Analysis for Tender 4 (reviewing state — partial analyses generated)
        Analysis::create([
            'tender_id' => $t4->id,
            'type' => 'consensus',
            'title' => 'Consensus Points Analysis',
            'content' => [
                'summary' => 'Strong alignment between systems architect and data engineer on core architectural approach.',
                'points' => [
                    ['area' => 'Stream Processing Architecture', 'agreement_level' => 90, 'detail' => 'Both participants recommend Apache Kafka as the central message transport with format-specific adapters.'],
                    ['area' => 'Edge Computing for Signage', 'agreement_level' => 85, 'detail' => 'Agreement that station-level processing is needed to eliminate network latency from passenger-facing displays.'],
                    ['area' => 'Data Quality Transparency', 'agreement_level' => 92, 'detail' => 'Both emphasize honest uncertainty over false precision — show confidence levels to passengers rather than hiding data issues.'],
                    ['area' => 'Zero-Downtime Operations', 'agreement_level' => 88, 'detail' => 'Consensus on containerized deployments with automated rollback, eliminating scheduled maintenance windows.'],
                ],
            ],
            'confidence' => 0.85,
        ]);

        Analysis::create([
            'tender_id' => $t4->id,
            'type' => 'gaps',
            'title' => 'Missing & Weak Areas Assessment',
            'content' => [
                'summary' => 'Two participants provide strong technical depth but leave operational and commercial areas underexplored.',
                'gaps' => [
                    ['area' => 'Cost Estimation', 'severity' => 'high', 'detail' => 'Neither participant provided estimates for per-station edge hardware, ongoing cloud compute costs, or operator onboarding costs.'],
                    ['area' => 'Third-Party API Monetization', 'severity' => 'medium', 'detail' => 'API for third-party developers mentioned in requirements but not addressed — rate limiting, authentication, pricing tiers undefined.'],
                    ['area' => 'Historical Data Retention', 'severity' => 'medium', 'detail' => 'No discussion of data retention policies, archival strategy, or analytics capabilities for transport planners.'],
                ],
            ],
            'confidence' => 0.79,
        ]);
    }
}
