<?php

namespace App\Livewire\Admin\Offers;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;

class OfferList extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = 'ALL'; // ALL, DRAFT, SCHEDULED, ACTIVE, EXPIRED

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTab()
    {
        $this->resetPage();
    }

    public function delete(Offer $offer)
    {
        $offer->delete();
    }

    public function render()
    {
        $query = Offer::query()
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });

        switch ($this->tab) {
            case 'DRAFT':
                $query->where('is_published', false);
                break;
            case 'SCHEDULED':
                $query->scheduled();
                break;
            case 'ACTIVE':
                $query->active();
                break;
            case 'EXPIRED':
                $query->expired();
                break;
        }

        $offers = $query->latest()->paginate(10);

        return view('livewire.admin.offers.offer-list', [
            'offers' => $offers,
        ])->layout('components.layouts.admin', ['title' => 'Offers | Admin']);
    }
}
