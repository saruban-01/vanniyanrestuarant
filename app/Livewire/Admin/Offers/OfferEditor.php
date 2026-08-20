<?php

namespace App\Livewire\Admin\Offers;

use App\Models\AuditLog;
use App\Models\Offer;
use Illuminate\Support\Str;
use Livewire\Component;

class OfferEditor extends Component
{
    public ?Offer $offer = null;

    // Form state
    public $title = '';
    public $slug = '';
    public $description = '';
    public $image_url = '';
    public $type = 'discount';
    public $price_or_discount = '';
    public $cta_text = '';
    public $cta_url = '';
    
    public $valid_from = null;
    public $valid_until = null;
    
    public $is_dine_in = true;
    public $is_takeaway = true;
    public $is_featured = false;
    public $is_active = true;
    public $is_published = false;
    public $terms = '';
    public $sort_order = 0;
    
    // SEO
    public $meta_title = '';
    public $meta_description = '';
    public $canonical_url = '';
    public $og_title = '';
    public $og_description = '';
    public $og_image = '';
    public $robots = 'index, follow';
    public $schema_type = '';
    public $seo_title = '';
    public $seo_description = '';

    public function mount(?Offer $offer = null)
    {
        if ($offer && $offer->exists) {
            $this->offer = $offer;
            $this->fill($offer->toArray());
            $this->valid_from = $offer->valid_from ? $offer->valid_from->format('Y-m-d\TH:i') : null;
            $this->valid_until = $offer->valid_until ? $offer->valid_until->format('Y-m-d\TH:i') : null;

            if ($seo = $offer->seoMetadata) {
                $this->meta_title = $seo->meta_title;
                $this->meta_description = $seo->meta_description;
                $this->canonical_url = $seo->canonical_url;
                $this->og_title = $seo->og_title;
                $this->og_description = $seo->og_description;
                $this->og_image = $seo->og_image;
                $this->robots = $seo->robots;
                $this->schema_type = $seo->schema_type;
            } else {
                $this->meta_title = $offer->seo_title ?? '';
                $this->meta_description = $offer->seo_description ?? '';
            }
        }
    }

    public function updatedTitle()
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save()
    {
        $data = $this->validate([
            'title' => 'required|max:255',
            'slug' => 'required|max:255|unique:offers,slug,' . ($this->offer->id ?? 'NULL'),
            'description' => 'nullable',
            'image_url' => 'nullable|url',
            'type' => 'required|in:discount,free_item,bundle',
            'price_or_discount' => 'nullable|max:255',
            'cta_text' => 'nullable|max:255',
            'cta_url' => 'nullable|url',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_dine_in' => 'boolean',
            'is_takeaway' => 'boolean',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'terms' => 'nullable',
            'seo_title' => 'nullable|max:255',
            'seo_description' => 'nullable',
        ]);

        $isCreating = !$this->offer;
        $oldValues = $isCreating ? [] : $this->offer->toArray();

        unset($data['seo_title']);
        unset($data['seo_description']);
        
        if ($isCreating) {
            $this->offer = Offer::create($data);
        } else {
            $this->offer->update($data);
        }

        $this->offer->seoMetadata()->updateOrCreate(
            [],
            [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'canonical_url' => $this->canonical_url,
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,
                'og_image' => $this->og_image,
                'robots' => $this->robots,
                'schema_type' => $this->schema_type,
            ]
        );

        // Audit Log
        AuditLog::create([
            'action' => $isCreating ? 'created' : 'updated',
            'entity_type' => Offer::class,
            'entity_id' => $this->offer->id,
            'old_values' => $isCreating ? null : $oldValues,
            'new_values' => $this->offer->fresh()->toArray(),
        ]);

        session()->flash('message', 'Offer successfully saved.');
        return redirect()->route('admin.offers');
    }

    public function render()
    {
        return view('livewire.admin.offers.offer-editor')->layout('components.layouts.admin', ['title' => 'Edit Offer | Admin']);
    }
}
