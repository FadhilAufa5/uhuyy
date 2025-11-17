<?php
use App\Livewire\Forms\BranchForm;
use App\Models\Branch;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use App\Jobs\ConvertPdfToJpgJob;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads; 

    public ?Branch $branch = null;
    public BranchForm $form;

    #[On('loadBranch')]
    public function loadBranch($id): void
    {
        if ($id) {
            $this->branch = Branch::find($id);
        } else {
            $this->resetForm();
        }
    }

    public function save(): void
{
    try {
        $validated = $this->validate();

        // Get original filename 
        $originalName = $this->form->file_path->getClientOriginalName();
        
        // Simpan file dengan nama asli
        $filePath = $this->form->file_path->storeAs('branches', $originalName, 'public');

        // Set semua branch user ini jadi tidak aktif
        Branch::where('user_id', auth()->id())->update(['status' => 'tidak aktif']); 

        // Simpan branch baru
        $branch = Branch::create([
            'user_id'   => auth()->id(),
            'file_path' => $filePath,
            'status'    => 'aktif',
        ]);

        // Log upload activity
        \App\Traits\LogsActivity::logCustomActivity(
            'uploaded',
            'Uploaded branch document: ' . $originalName,
            [
                'branch_id' => $branch->id,
                'file_name' => $originalName,
                'file_path' => $filePath,
            ]
        );

        // Kirim job ke queue
        ConvertPdfToJpgJob::dispatch($branch);

        // Notifikasi sukses
        $this->dispatch('notify', title: 'success', message: 'File berhasil diupload.');

        // Reset & tutup modal
        $this->reset();
        Flux::modals()->close();
        $this->dispatch('refresh-branch-table');
        $this->dispatch('refresh-hero');

    } catch (\Exception $e) {
        $this->dispatch('notify', title: 'error', message: 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

    public function resetForm()
    {
        $this->reset();
    }
};

?>
    <section class="space-y-6">
    <flux:modal name="add-branch" :show="$errors->isNotEmpty() || $branch"
                @close="resetForm()"
                class="w-2xl space-y-6">

        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div>
                <flux:heading size="lg">
                    {{ $branch ? 'Update Branch' : 'Tambah Data' }}
                </flux:heading>
                <flux:subheading>
                    {{ $branch ? 'Make changes to Branch.' : 'Masukan data Branch baru.' }}
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">
                <flux:field>
                    <flux:label>Upload File (PDF saja)</flux:label>
                    <label class="block w-full">
                        <input type="file" wire:model="form.file_path" accept="application/pdf"
                               class="hidden" id="pdf-upload" />
                        <label for="pdf-upload"
                               class="cursor-pointer inline-block px-4 py-2 rounded-full bg-blue-50 text-blue-700 font-semibold border-0 hover:bg-blue-100">
                            Choose PDF
                        </label>
                    </label>
                    <flux:error name="form.file_path" />
                    @if($form && $form->file_path)
                        <div class="mt-2 text-sm text-gray-600">
                            File: {{ $form->file_path->getClientOriginalName() }}
                        </div>
                    @endif
                </flux:field>
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="form.file_path, save">
                    {{ $branch ? 'Update' : 'Simpan' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>


