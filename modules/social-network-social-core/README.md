# Liberu Social Network Social Core

The provider-neutral Social Core module owns tenant-scoped network settings,
deployment mode, terminology, feature policy, shared IDs, and lifecycle events.
It does not depend on HTTP, Filament, Livewire, or application classes for its
domain behavior.

The matching API, Filament, and Livewire adapters are separate packages and
must delegate to this module's actions and policies.
