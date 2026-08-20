<?php

namespace App\Livewire\Admin\Settings;

use App\Models\AuditLog;
use App\Models\LoyaltyConfig;
use Livewire\Component;

class LoyaltyEditor extends Component
{
    public ?LoyaltyConfig $config = null;

    // Form state
    public $heading = '';
    public $description = '';
    public $card_image_url = '';
    public $visit_5_title = '';
    public $visit_5_reward = '';
    public $visit_10_title = '';
    public $visit_10_reward = '';
    public $how_it_works = '';
    public $terms = '';
    public $cta_text = '';
    public $is_visible = true;

    public function mount()
    {
        $this->config = LoyaltyConfig::getActive();
        if ($this->config) {
            $this->fill($this->config->toArray());
            // Decode JSON for textarea
            $this->how_it_works = json_encode($this->config->how_it_works, JSON_PRETTY_PRINT);
            $this->terms = is_array($this->config->terms) ? implode("\n", $this->config->terms) : '';
        }
    }

    public function save()
    {
        $this->validate([
            'heading' => 'required|max:255',
            'description' => 'required',
            'card_image_url' => 'nullable|url',
            'visit_5_title' => 'required|max:255',
            'visit_5_reward' => 'required|max:255',
            'visit_10_title' => 'required|max:255',
            'visit_10_reward' => 'required|max:255',
            'how_it_works' => 'nullable|json',
            'terms' => 'nullable',
            'cta_text' => 'required|max:255',
            'is_visible' => 'boolean',
        ]);

        $oldValues = $this->config ? $this->config->toArray() : [];

        $termsArray = array_filter(array_map('trim', explode("\n", $this->terms)));

        $data = [
            'heading' => $this->heading,
            'description' => $this->description,
            'card_image_url' => $this->card_image_url,
            'visit_5_title' => $this->visit_5_title,
            'visit_5_reward' => $this->visit_5_reward,
            'visit_10_title' => $this->visit_10_title,
            'visit_10_reward' => $this->visit_10_reward,
            'how_it_works' => json_decode($this->how_it_works, true) ?? [],
            'terms' => $termsArray,
            'cta_text' => $this->cta_text,
            'is_visible' => $this->is_visible,
        ];

        if (!$this->config) {
            $this->config = LoyaltyConfig::create($data);
        } else {
            $this->config->update($data);
        }

        // Audit Log
        AuditLog::create([
            'action' => 'updated_loyalty_config',
            'entity_type' => LoyaltyConfig::class,
            'entity_id' => $this->config->id,
            'old_values' => $oldValues,
            'new_values' => $this->config->fresh()->toArray(),
        ]);

        session()->flash('message', 'Loyalty Card details successfully saved.');
    }

    public function render()
    {
        return view('livewire.admin.settings.loyalty-editor')->layout('components.layouts.admin', ['title' => 'Loyalty Config | Admin']);
    }
}
