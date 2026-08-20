<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AuditLog;
use App\Services\LegalService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Legal extends Component
{
    public string $privacyDraft = '';

    public string $privacyPublishedAt = '';

    public string $termsDraft = '';

    public string $termsPublishedAt = '';

    public string $governingLaw = '';

    public string $privacyStatus = '';

    public string $termsStatus = '';

    protected function rules(): array
    {
        return [
            'privacyDraft' => ['nullable', 'string'],
            'privacyPublishedAt' => ['nullable', 'date'],
            'termsDraft' => ['nullable', 'string'],
            'termsPublishedAt' => ['nullable', 'date'],
            'governingLaw' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function mount(LegalService $legal): void
    {
        $this->privacyDraft = $legal->draft(LegalService::DOC_PRIVACY);
        $this->privacyPublishedAt = $legal->publishedAt(LegalService::DOC_PRIVACY) ?? now('Asia/Colombo')->toDateString();
        $this->termsDraft = $legal->draft(LegalService::DOC_TERMS);
        $this->termsPublishedAt = $legal->publishedAt(LegalService::DOC_TERMS) ?? now('Asia/Colombo')->toDateString();
        $this->governingLaw = $legal->governingLaw();

        $this->privacyStatus = $this->statusLine($legal, LegalService::DOC_PRIVACY);
        $this->termsStatus = $this->statusLine($legal, LegalService::DOC_TERMS);
    }

    public function savePrivacyDraft(LegalService $legal): void
    {
        $this->validateOnly('privacyDraft');

        $legal->saveDraft(LegalService::DOC_PRIVACY, $this->privacyDraft);

        AuditLog::log('LEGAL_DRAFT_SAVED', 'Privacy Policy draft saved.', ['module' => 'LEGAL', 'doc' => 'privacy']);

        session()->flash('message', 'Privacy Policy draft saved. It is not yet visible to visitors.');
    }

    public function publishPrivacy(LegalService $legal): void
    {
        $this->validate();

        $legal->publish(
            LegalService::DOC_PRIVACY,
            $this->privacyDraft,
            $this->privacyPublishedAt,
            auth('admin')->id(),
        );

        $this->privacyStatus = $this->statusLine($legal, LegalService::DOC_PRIVACY);

        AuditLog::log('LEGAL_PUBLISHED', 'Privacy Policy published.', ['module' => 'LEGAL', 'doc' => 'privacy']);

        session()->flash('message', 'Privacy Policy published. Visitors now see the updated version.');
    }

    public function saveTermsDraft(LegalService $legal): void
    {
        $this->validateOnly('termsDraft');

        $legal->saveDraft(LegalService::DOC_TERMS, $this->termsDraft);

        AuditLog::log('LEGAL_DRAFT_SAVED', 'Terms & Conditions draft saved.', ['module' => 'LEGAL', 'doc' => 'terms']);

        session()->flash('message', 'Terms & Conditions draft saved. It is not yet visible to visitors.');
    }

    public function publishTerms(LegalService $legal): void
    {
        $this->validate();

        $legal->publish(
            LegalService::DOC_TERMS,
            $this->termsDraft,
            $this->termsPublishedAt,
            auth('admin')->id(),
        );

        $this->termsStatus = $this->statusLine($legal, LegalService::DOC_TERMS);

        AuditLog::log('LEGAL_PUBLISHED', 'Terms & Conditions published.', ['module' => 'LEGAL', 'doc' => 'terms']);

        session()->flash('message', 'Terms & Conditions published. Visitors now see the updated version.');
    }

    public function saveGoverningLaw(LegalService $legal): void
    {
        $this->validateOnly('governingLaw');

        $legal->saveGoverningLaw($this->governingLaw);

        AuditLog::log('LEGAL_SETTINGS_UPDATED', 'Legal settings updated.', ['module' => 'LEGAL', 'governing_law' => $this->governingLaw]);

        session()->flash('message', 'Governing law saved.');
    }

    private function statusLine(LegalService $legal, string $doc): string
    {
        $date = $legal->publishedAt($doc);
        $by = $legal->updatedBy($doc);

        $line = $date ? 'Published '.$date : 'Not yet published';

        if ($by) {
            $line .= ' by '.$by;
        }

        $line .= '. Draft '.($legal->draft($doc) !== '' ? 'has content' : 'is empty').'.';

        return $line;
    }

    public function render()
    {
        return view('livewire.admin.settings.legal');
    }
}