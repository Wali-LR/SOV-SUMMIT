<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SeoGeneratorController extends Controller
{
    public function seo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'context' => ['nullable', 'string', 'max:2000'],
        ]);

        $prompt = $this->seoPrompt($data['title'], $data['location'] ?? null, $data['context'] ?? null);

        $result = $this->callOpenAi(
            system: 'You are an SEO copy assistant for SOV SUMMIT, a Swiss international event and delegation coordination company. Write in British English, executive tone, no hype. Respond ONLY with valid minified JSON matching the requested schema. Never include markdown, code fences, or commentary.',
            user: $prompt,
            maxTokens: (int) config('services.openai.max_tokens'),
        );

        if ($result['ok'] === false) {
            return response()->json($result['payload'], $result['status']);
        }

        return response()->json([
            'seo_title' => trim((string) ($result['data']['seo_title'] ?? '')),
            'summary' => trim((string) ($result['data']['summary'] ?? $result['data']['description'] ?? '')),
            'seo_keywords' => trim((string) ($result['data']['seo_keywords'] ?? $result['data']['keywords'] ?? '')),
        ]);
    }

    public function description(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:2000'],
        ]);

        $prompt = $this->descriptionPrompt($data['title'], $data['location'] ?? null, $data['summary'] ?? null);

        $result = $this->callOpenAi(
            system: 'You are a senior copywriter for SOV SUMMIT, a Swiss international event and delegation coordination company. Write in British English with an executive, restrained, precise voice — no hype, no exclamation marks, no emojis. Return ONLY valid minified JSON — never markdown or commentary. The "description" field must be well-formed HTML using <h3>, <p>, <strong>, <em>, <ul>, <ol>, <li>, <br>. Do not include headings above <h3>, links, images, or inline styles. Use the best of your general knowledge to add relevant industry context (destinations, formats, typical attendees, operational considerations) — do not fabricate specific dates, prices, speakers, or partner names.',
            user: $prompt,
            maxTokens: 1600,
        );

        if ($result['ok'] === false) {
            return response()->json($result['payload'], $result['status']);
        }

        return response()->json([
            'description' => trim((string) ($result['data']['description'] ?? '')),
        ]);
    }

    public function blogSeo(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'context' => ['nullable', 'string', 'max:2000'],
        ]);

        $prompt = $this->blogSeoPrompt($data['title'], $data['category'] ?? null, $data['context'] ?? null);

        $result = $this->callOpenAi(
            system: 'You are an SEO copy assistant for SOV SUMMIT, a Swiss international event and delegation coordination company. You are writing metadata for a blog article. Write in British English, executive tone, no hype. Respond ONLY with valid minified JSON matching the requested schema. Never include markdown, code fences, or commentary.',
            user: $prompt,
            maxTokens: (int) config('services.openai.max_tokens'),
        );

        if ($result['ok'] === false) {
            return response()->json($result['payload'], $result['status']);
        }

        return response()->json([
            'seo_title' => trim((string) ($result['data']['seo_title'] ?? '')),
            'summary' => trim((string) ($result['data']['summary'] ?? $result['data']['description'] ?? '')),
            'seo_keywords' => trim((string) ($result['data']['seo_keywords'] ?? $result['data']['keywords'] ?? '')),
        ]);
    }

    public function blogDescription(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:2000'],
        ]);

        $prompt = $this->blogDescriptionPrompt($data['title'], $data['category'] ?? null, $data['summary'] ?? null);

        $result = $this->callOpenAi(
            system: 'You are a senior editorial writer for SOV SUMMIT, a Swiss international event and delegation coordination company. You are drafting a long-form blog article body. Write in British English with an executive, restrained, precise voice — no hype, no exclamation marks, no emojis. Return ONLY valid minified JSON — never markdown or commentary. The "description" field must be well-formed HTML using <h3>, <p>, <strong>, <em>, <ul>, <ol>, <li>, <br>. Do not include headings above <h3>, links, images, or inline styles. Draw on relevant industry knowledge for context (formats, stakeholders, operational considerations) — do not fabricate specific dates, prices, named individuals, or partner organisations.',
            user: $prompt,
            maxTokens: 1800,
        );

        if ($result['ok'] === false) {
            return response()->json($result['payload'], $result['status']);
        }

        return response()->json([
            'description' => trim((string) ($result['data']['description'] ?? '')),
        ]);
    }

    private function seoPrompt(string $title, ?string $location, ?string $context): string
    {
        $lines = [
            'Write SEO metadata for an event titled: "'.$title.'".',
        ];
        if ($location) {
            $lines[] = 'Location: '.$location.'.';
        }
        if ($context) {
            $lines[] = 'Additional context: '.$context;
        }
        $lines[] = 'Return JSON with exactly these fields:';
        $lines[] = '- "seo_title": SEO page title, STRICT 50 to 60 characters. Count carefully. Do NOT exceed 60 characters. Do NOT append subtitles, pipe separators, brand names, or descriptive tails. Keep it tight — just the event identity and, if space allows within 60, the location.';
        $lines[] = '- "summary": meta description, ONE sentence, 130-155 characters. Do not repeat the seo_title verbatim.';
        $lines[] = '- "seo_keywords": 6-10 relevant keywords/phrases, comma-separated, lowercase, no hashtags.';
        $lines[] = 'Before returning, silently count the characters of seo_title and shorten it if it exceeds 60.';
        return implode("\n", $lines);
    }

    private function descriptionPrompt(string $title, ?string $location, ?string $summary): string
    {
        $lines = [
            'Write a long-form HTML description for an event titled: "'.$title.'".',
        ];
        if ($location) {
            $lines[] = 'Location: '.$location.'.';
        }
        if ($summary) {
            $lines[] = 'Editor summary: '.$summary;
        }
        $lines[] = 'Return JSON with exactly this field:';
        $lines[] = '- "description": HTML body copy, roughly 1500 characters of rendered text (about 220-280 words). Structure it as:';
        $lines[] = '  1) Opening <p> (2-3 sentences): what the event is, who it serves, why it exists. Add one specific detail about the location or format drawn from general knowledge, without inventing dates, prices, or names.';
        $lines[] = '  2) <h3>Programme focus</h3> followed by a <p> (2-3 sentences) describing the intent and themes.';
        $lines[] = '  3) <h3>Coordination scope</h3> followed by a <p> (2-3 sentences) describing what SOV SUMMIT delivers (venue, hospitality, logistics, security, protocol, media, transport — pick what fits the event type).';
        $lines[] = '  4) <h3>Programme highlights</h3> followed by a <ul> with 4-6 <li> bullets naming concrete deliverables or session types.';
        $lines[] = '  5) Closing <p> (1-2 sentences): invite qualified enquiries without hard sell.';
        $lines[] = 'Rules: use only <h3>, <p>, <strong>, <em>, <ul>, <ol>, <li>, <br>. No inline styles, no links, no headings above <h3>, no emojis, no exclamation marks. Do not fabricate specific dates, prices, speaker names, partner names, or attendee numbers.';
        return implode("\n", $lines);
    }

    private function blogSeoPrompt(string $title, ?string $category, ?string $context): string
    {
        $lines = [
            'Write SEO metadata for a blog article titled: "'.$title.'".',
        ];
        if ($category) {
            $lines[] = 'Category: '.$category.'.';
        }
        if ($context) {
            $lines[] = 'Article context: '.$context;
        }
        $lines[] = 'Return JSON with exactly these fields:';
        $lines[] = '- "seo_title": SEO page title, STRICT 50 to 60 characters. Count carefully. Do NOT exceed 60 characters. Do NOT append pipe separators or brand names.';
        $lines[] = '- "summary": meta description, ONE sentence, 130-155 characters. Do not repeat the seo_title verbatim.';
        $lines[] = '- "seo_keywords": 6-10 relevant keywords/phrases, comma-separated, lowercase, no hashtags.';
        $lines[] = 'Before returning, silently count the characters of seo_title and shorten it if it exceeds 60.';
        return implode("\n", $lines);
    }

    private function blogDescriptionPrompt(string $title, ?string $category, ?string $summary): string
    {
        $lines = [
            'Write a long-form HTML body for a blog article titled: "'.$title.'".',
        ];
        if ($category) {
            $lines[] = 'Category: '.$category.'.';
        }
        if ($summary) {
            $lines[] = 'Editor summary: '.$summary;
        }
        $lines[] = 'Return JSON with exactly this field:';
        $lines[] = '- "description": HTML article body, roughly 2000-2500 characters of rendered text (about 320-400 words). Structure it as:';
        $lines[] = '  1) Opening <p> (2-3 sentences): set up the topic, why it matters now, who should care.';
        $lines[] = '  2) <h3>Context</h3> followed by 1-2 <p> paragraphs framing the operational or industry backdrop.';
        $lines[] = '  3) <h3>Key considerations</h3> followed by a <ul> with 4-6 <li> bullets — each bullet a concrete point, not a slogan.';
        $lines[] = '  4) <h3>How SOV SUMMIT approaches this</h3> followed by 1-2 <p> paragraphs on the coordination angle (venue, hospitality, logistics, security, protocol, media, transport — whichever fits).';
        $lines[] = '  5) Closing <p> (1-2 sentences): invite qualified enquiries without hard sell.';
        $lines[] = 'Rules: use only <h3>, <p>, <strong>, <em>, <ul>, <ol>, <li>, <br>. No inline styles, no links, no headings above <h3>, no emojis, no exclamation marks. Do not fabricate specific dates, prices, speaker names, partner names, statistics, or attendee numbers.';
        return implode("\n", $lines);
    }

    private function callOpenAi(string $system, string $user, int $maxTokens): array
    {
        $apiKey = config('services.openai.key');
        if (!$apiKey) {
            return ['ok' => false, 'status' => 500, 'payload' => ['error' => 'OpenAI API key is not configured.']];
        }

        $response = Http::withToken($apiKey)
            ->timeout(45)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model'),
                'temperature' => config('services.openai.temperature'),
                'max_tokens' => $maxTokens,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
            ]);

        if (!$response->successful()) {
            return [
                'ok' => false,
                'status' => 502,
                'payload' => [
                    'error' => 'OpenAI request failed.',
                    'detail' => $response->json('error.message') ?? $response->body(),
                ],
            ];
        }

        $content = $response->json('choices.0.message.content');
        $parsed = json_decode((string) $content, true);

        if (!is_array($parsed)) {
            return [
                'ok' => false,
                'status' => 502,
                'payload' => ['error' => 'Could not parse AI response as JSON.', 'raw' => $content],
            ];
        }

        return ['ok' => true, 'data' => $parsed];
    }
}
