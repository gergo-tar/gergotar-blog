<div>
    @if (! session('status'))
    <form class="flex flex-col lg:flex-row" wire:submit="subscribe">
        <div class="relative flex flex-col lg:mr-2 lg:w-1/2">
            <input type="string" wire:model.blur="name" placeholder="{{ __('Newsletter::newsletter.subscribe.placeholder.name') }}"
                class="w-full px-5 py-4 font-light transition-colors border bg-grey-lightest font-body text-primary focus:border-secondary focus:outline-none focus:ring-2 focus:ring-secondary {{ $errors->has('name') ? 'border-red placeholder-red' : 'border-primary dark:border-secondary placeholder-primary' }}"
                @error('name') aria-invalid="true" @enderror />
            <div class="relative bottom-0 lg:-mb-6 dark:mt-2 dark:lg:mt-0">
                @error('name') <span class="lg:absolute dark:p-2 text-red dark:text-white dark:bg-red">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="relative flex flex-col mt-5 lg:mt-0 lg:w-1/2">
            <input type="email" wire:model.blur="email" placeholder="{{ __('Newsletter::newsletter.subscribe.placeholder.email') }}"
                class="w-full px-5 py-4 font-light transition-colors border bg-grey-lightest font-body text-primary focus:border-secondary focus:outline-none focus:ring-2 focus:ring-secondary {{ $errors->has('email') ? 'border-red placeholder-red' : 'border-primary dark:border-secondary placeholder-primary' }}"
                @error('email') aria-invalid="true" @enderror/>
            <div class="relative bottom-0 lg:-mb-6 dark:mt-2 dark:lg:mt-0">
                @error('email') <span class="lg:absolute dark:p-2 text-red dark:text-white dark:bg-red">{{ $message }}</span> @enderror
            </div>
        </div>

        <button type="submit"
            class="px-10 py-3 mt-5 text-xl font-semibold text-white border bg-secondary font-body hover:bg-green lg:mt-0 md:text-2xl border-secondary">
            {{ __('Newsletter::newsletter.subscribe.cta') }}
        </button>
    </form>
    @else
        <div class="pt-5 text-green">
            {{ session('status') }}
        </div>
    @endif
</div>
