<div>
    @if (!session('status'))
        <form class="pt-16" wire:submit="send">
            <div class="flex flex-col sm:flex-row">
                <div class="relative w-full sm:mr-3 sm:w-1/2">
                    <label class="block pb-3 font-medium font-body text-primary dark:text-white">
                        {{ __('Form::contact.fields.name.label') }}
                    </label>
                    <input type="text" wire:model.blur="name"
                        placeholder="{{ __('Form::contact.fields.name.placeholder') }}"
                        class="w-full px-5 py-4 font-light transition-colors border bg-grey-lightest font-body text-primary focus:border-secondary focus:outline-none focus:ring-2 focus:ring-secondary dark:text-white {{ $errors->has('email') ? 'border-red placeholder-red' : 'border-primary dark:border-secondary placeholder-primary' }}" />
                    <div class="absolute bottom-0 -mb-6 dark:bg-red">
                        @error('name')
                            <span class="dark:p-2 text-red dark:text-white">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="relative w-full pt-6 sm:ml-3 sm:w-1/2 sm:pt-0">
                    <label class="block pb-3 font-medium font-body text-primary dark:text-white">
                        {{ __('Form::contact.fields.email.label') }}
                    </label>
                    <input type="email" wire:model.blur="email"
                        placeholder="{{ __('Form::contact.fields.email.placeholder') }}"
                        class="w-full px-5 py-4 font-light transition-colors border bg-grey-lightest font-body text-primary focus:border-secondary focus:outline-none focus:ring-2 focus:ring-secondary dark:text-white {{ $errors->has('email') ? 'border-red placeholder-red' : 'border-primary dark:border-secondary placeholder-primary' }}" />
                    <div class="absolute bottom-0 -mb-6 dark:bg-red">
                        @error('email')
                            <span class="dark:p-2 text-red dark:text-white">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="relative w-full pt-6 sm:pt-10">
                <label class="block pb-3 font-medium font-body text-primary dark:text-white">
                    {{ __('Form::contact.fields.message.label') }}
                </label>
                <textarea wire:model.blur="message" cols="30" rows="9"
                    placeholder="{{ __('Form::contact.fields.message.placeholder') }}"
                    class="w-full px-5 py-4 font-light transition-colors border  bg-grey-lightest font-body text-primary focus:border-secondary focus:outline-none focus:ring-2 focus:ring-secondary dark:text-white {{ $errors->has('email') ? 'border-red placeholder-red' : 'border-primary dark:border-secondary placeholder-primary' }}"></textarea>
                <div class="absolute bottom-0 -mb-6 dark:bg-red">
                    @error('message')
                        <span class="dark:p-2 text-red dark:text-white">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button
                class="block px-10 py-4 mt-10 mb-12 text-xl font-semibold text-center text-white transition-colors bg-secondary font-body hover:bg-green sm:inline-block sm:text-left sm:text-2xl">
                {{ __('Form::contact.cta') }}
            </button>
        </form>
    @else
        <div class="pt-5 text-green">
            {{ session('status') }}
        </div>
        {{-- New message button --}}
        <div class="pt-10">
            <a href="{{ route('contact') }}" wire:submit="reload"
                class="block px-6 py-2 text-lg font-semibold text-center text-white transition-colors bg-secondary font-body hover:bg-green sm:inline-block sm:text-left sm:text-xl">
                {{ __('Form::contact.new') }}
            </a>
        </div>
    @endif
</div>
