<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalysisController extends Controller
{
    public function show(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $tender->load(['analyses', 'participants', 'criteria.questions.responses']);

        return view('tenders.analysis', compact('tender'));
    }

    public function generate(Request $request, Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $type = $request->input('type', 'consensus');

        // Simulated AI analysis for demo
        $content = $this->simulateAnalysis($tender, $type);

        $tender->analyses()->create([
            'type' => $type,
            'title' => $this->getAnalysisTitle($type),
            'content' => $content,
            'confidence' => rand(65, 95) / 100,
        ]);

        return back()->with('success', ucfirst($type) . ' analysis generated.');
    }

    public function sessionPrep(Tender $tender)
    {
        abort_unless($tender->user_id === Auth::id(), 403);

        $tender->load(['analyses', 'criteria.questions', 'participants']);

        return view('tenders.session-prep', compact('tender'));
    }

    private function getAnalysisTitle(string $type): string
    {
        return match ($type) {
            'consensus' => 'Consensus Points Analysis',
            'conflicts' => 'Conflicting Viewpoints Report',
            'gaps' => 'Missing & Weak Areas Assessment',
            'themes' => 'Recurring Themes Identification',
            'risks' => 'Risk Signal Detection',
            'insights' => 'Notable Insights Summary',
            'session_prep' => 'Session Preparation Brief',
        };
    }

    private function simulateAnalysis(Tender $tender, string $type): array
    {
        $tenderName = $tender->name;

        return match ($type) {
            'consensus' => [
                'summary' => "Analysis of participant responses for \"{$tenderName}\" reveals strong alignment in several key areas.",
                'points' => [
                    ['area' => 'Technical Architecture', 'agreement_level' => 92, 'detail' => 'All participants favor a modular microservices approach with API-first design.'],
                    ['area' => 'Timeline Feasibility', 'agreement_level' => 78, 'detail' => 'Broad agreement that the proposed 6-month timeline is achievable with phased delivery.'],
                    ['area' => 'Quality Standards', 'agreement_level' => 88, 'detail' => 'Strong consensus on ISO 27001 compliance and automated testing requirements.'],
                    ['area' => 'Risk Management', 'agreement_level' => 71, 'detail' => 'Moderate agreement on key risk areas, though mitigation strategies differ.'],
                ],
            ],
            'conflicts' => [
                'summary' => "Identified divergent perspectives across participant responses that require facilitated discussion.",
                'conflicts' => [
                    ['topic' => 'Cloud Provider Selection', 'positions' => ['AWS preferred for enterprise features', 'Azure preferred for existing integrations', 'Multi-cloud strategy recommended'], 'severity' => 'high'],
                    ['topic' => 'Development Methodology', 'positions' => ['Pure Agile/Scrum approach', 'Hybrid Agile-Waterfall for compliance milestones'], 'severity' => 'medium'],
                    ['topic' => 'Data Residency', 'positions' => ['EU-only hosting', 'Distributed with primary EU region'], 'severity' => 'high'],
                ],
            ],
            'gaps' => [
                'summary' => "Several critical areas lack sufficient detail or expert coverage in current responses.",
                'gaps' => [
                    ['area' => 'Disaster Recovery', 'severity' => 'critical', 'detail' => 'No participant provided detailed RPO/RTO targets or failover procedures.'],
                    ['area' => 'Accessibility Compliance', 'severity' => 'high', 'detail' => 'WCAG 2.1 AA requirements mentioned but no implementation strategy provided.'],
                    ['area' => 'Integration Testing', 'severity' => 'medium', 'detail' => 'Unit testing well-covered, but end-to-end and integration testing approaches are sparse.'],
                    ['area' => 'User Training Plan', 'severity' => 'medium', 'detail' => 'Limited discussion of change management and end-user onboarding.'],
                ],
            ],
            'themes' => [
                'summary' => "Semantic analysis reveals recurring patterns across all participant responses.",
                'themes' => [
                    ['theme' => 'Security-First Design', 'frequency' => 14, 'participants_mentioning' => 4, 'sample_quotes' => ['Security must be embedded from architecture level, not bolted on']],
                    ['theme' => 'Iterative Delivery', 'frequency' => 11, 'participants_mentioning' => 3, 'sample_quotes' => ['Phased rollout reduces risk and enables early feedback']],
                    ['theme' => 'Automation Priority', 'frequency' => 9, 'participants_mentioning' => 4, 'sample_quotes' => ['CI/CD pipeline essential from day one']],
                    ['theme' => 'Stakeholder Communication', 'frequency' => 7, 'participants_mentioning' => 3, 'sample_quotes' => ['Weekly status reports with visual dashboards']],
                ],
            ],
            'risks' => [
                'summary' => "Risk signals detected from response analysis and cross-referencing with tender requirements.",
                'risks' => [
                    ['risk' => 'Scope Creep Potential', 'probability' => 'high', 'impact' => 'high', 'signal' => 'Multiple participants noted ambiguity in integration requirements section 4.2.'],
                    ['risk' => 'Timeline Compression', 'probability' => 'medium', 'impact' => 'high', 'signal' => 'Dependency on third-party API availability not factored into estimates.'],
                    ['risk' => 'Single Point of Failure', 'probability' => 'medium', 'impact' => 'critical', 'signal' => 'Key architectural decisions concentrated with one participant.'],
                    ['risk' => 'Compliance Gap', 'probability' => 'low', 'impact' => 'critical', 'signal' => 'GDPR data processing agreement requirements not fully addressed.'],
                ],
            ],
            'insights' => [
                'summary' => "Notable patterns and non-obvious findings from deep analysis of all responses.",
                'insights' => [
                    ['insight' => 'Participants with prior public sector experience consistently emphasize documentation and audit trails, suggesting procurement compliance should be a core architecture feature rather than an afterthought.', 'relevance' => 'high'],
                    ['insight' => 'The strongest technical proposals all mention event-driven architecture, suggesting this pattern may be emerging as a de facto standard for similar platforms.', 'relevance' => 'high'],
                    ['insight' => 'Response depth correlates with criterion weight — experts naturally invest more effort in higher-weighted criteria, validating the weighting system.', 'relevance' => 'medium'],
                ],
            ],
            'session_prep' => [
                'discussion_topics' => [
                    ['topic' => 'Resolve cloud provider strategy', 'priority' => 'high', 'time_estimate' => '20 min', 'context' => 'Three competing positions need resolution before architecture can proceed.'],
                    ['topic' => 'Define disaster recovery requirements', 'priority' => 'critical', 'time_estimate' => '15 min', 'context' => 'Gap identified — no RPO/RTO targets established.'],
                    ['topic' => 'Align on development methodology', 'priority' => 'medium', 'time_estimate' => '10 min', 'context' => 'Minor conflict between pure Agile and hybrid approach.'],
                ],
                'clarification_questions' => [
                    'What is the maximum acceptable downtime during deployment windows?',
                    'Are there existing enterprise SSO systems that must be integrated?',
                    'What is the expected concurrent user load at peak?',
                ],
                'decision_points' => [
                    'Cloud provider commitment (AWS vs Azure vs Multi-cloud)',
                    'Data residency policy (EU-only vs distributed)',
                    'Go/No-Go criteria for Phase 1 release',
                ],
            ],
        };
    }
}
