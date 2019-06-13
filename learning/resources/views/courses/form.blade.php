@extends('layouts.app')

@section('jumbotron')
    @include('partials.jumbotron', ['title' => 'Registrar o actualizar un curso', 'icon' => 'edit'])
@endsection

@section('content')
    <div class="pl-5 pr-5">
        <form
            method="POST"
            action="{{ ! $course->id ? route('courses.store') : route('courses.update', ['slug' => $course->slug])}}"
            novalidate
            enctype="multipart/form-data"
        >
            {{-- Cone sto comprobamos si estamos en modo "edicion", o sea, para actualizar un curso --}}
            @if($course->id)
                @method('PUT')
            @endif

            @csrf

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ __("Información del curso") }}
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    {{ __("Nombre del curso") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        name="name"
                                        id="name"
                                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        value="{{ old('name') ?: $course->name }}"
                                        required
                                        autofocus
                                    />
                                    {{-- sie xiste algun tipo de error con el campo name, mostraremos estos errores  --}}
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label
                                    for="level_id"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __("Nivel del curso") }}
                                </label>
                                <div class="col-md-6">
                                    <select name="level_id" id="level_id" class="form-control">
                                        @foreach(\App\Level::pluck('name', 'id') as $id => $level)
                                            <option {{ (int) old('level_id') === $id || $course->level_id === $id ? 'selected' : '' }} value="{{ $id }}">
                                                {{ $level }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category_id" class="col-md-4 col-form-label text-md-right">{{ __("Categoría del curso") }}</label>
                            <div class="col-md-6">
                                <select name="category_id" id="category_id" class="form-control">
                                    {{-- cON \App\Category::groupBy('name')->pluck('name', 'id') evitamos que apareyca repetidos
                                     las categorias pero por algun motivo que no comprendo me da un error, asi que
                                     por eso lo quito--}}
                                    @foreach(\App\Category::groupBy('name')->pluck('name', 'id') as $id => $category)
                                        <option {{ (int) old('category_id') === $id || $course->category_id === $id ? 'selected' : '' }} value="{{ $id }}">
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group ml-3 mr-2">
                            <div class="col-md-6 offset-4">
                                <input
                                    type="file"
                                    class="custom-file-input{{ $errors->has('picture') ? ' is-invalid' : ''}}"
                                    id="picture"
                                    name="picture"
                                />
                                <label
                                    class="custom-file-label" for="picture"
                                >
                                    {{ __("Escoge una imagen para tu curso") }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label
                                for="description"
                                class="col-md-4 col-form-label text-md-right">
                                {{ __("Descripción del curso") }}
                            </label>
                            <div class="col-md-6">
                                <textarea
                                    id="description"
                                    class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    name="description"
                                    required
                                    rows="3"
                                >{{ old('description') ?: $course->description }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __("Requisitos para tomar el curso") }}</div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label
                                    for="requirement1"
                                    class="col-md-4 col-form-label text-md-right"
                                >
                                    {{ __("Requerimiento 1") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        id="requirement1"
                                        class="form-control{{ $errors->has('requirements.0') ? ' is-invalid' : '' }}"
                                        name="requirements[]"
                                        value="{{ old('requirements.0') ? old('requirements.0') : ($course->requirements_count > 0 ? $course->requirements[0]->requirement : '') }}"
                                    />
                                    @if ($errors->has('requirements.0'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('requirements.0') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                @if($course->requirements_count > 0)
                                    <input
                                        type="hidden"
                                        name="requirement_id0"
                                        value="{{ $course->requirements[0]->id }}"
                                    />
                                @endif
                            </div>

                            <div class="form-group row">
                                <label
                                    for="requirement2"
                                    class="col-sm-4 col-form-label text-md-right"
                                >
                                    {{ __("Requerimiento 2") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        id="requirement2"
                                        class="form-control{{ $errors->has('requirements.1') ? ' is-invalid' : '' }}"
                                        name="requirements[]"
                                        value="{{ old('requirements.1') ? old('requirements.1') : ($course->requirements_count > 1 ? $course->requirements[1]->requirement : '') }}"
                                    />

                                    @if ($errors->has('requirements.1'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('requirements.1') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @if($course->requirements_count > 1)
                                    <input
                                        type="hidden"
                                        name="requirement_id1"
                                        value="{{ $course->requirements[1]->id }}"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __("¿Qué conseguirá el estudiante al finalizar el curso?") }}</div>

                        <div class="card-body">
                            <div class="form-group row">
                                <label
                                    for="goal1"
                                    class="col-sm-4 col-form-label text-md-right"
                                >
                                    {{ __("Meta 1") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        id="goal1"
                                        class="form-control{{ $errors->has('goals.0') ? ' is-invalid' : '' }}"
                                        name="goals[]"
                                        value="{{ old('goals.0') ? old('goals.0') : ($course->goals_count > 0 ? $course->goals[0]->goal : '') }}"
                                    />
                                    @if ($errors->has('goals.0'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('goals.0') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @if($course->goals_count > 0)
                                    <input type="hidden" name="goal_id0" value="{{ $course->goals[0]->id }}" />
                                @endif

                            </div>

                            <div class="form-group row">
                                <label
                                    for="goal2"
                                    class="col-sm-4 col-form-label text-md-right"
                                >
                                    {{ __("Meta 2") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        id="goal2"
                                        class="form-control{{ $errors->has('goals.1') ? ' is-invalid' : '' }}"
                                        name="goals[]"
                                        value="{{ old('goals.1') ? old('goals.1') : ($course->goals_count > 1 ? $course->goals[1]->goal : '') }}"
                                    />
                                    @if ($errors->has('goals.1'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('goals.1') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @if($course->goals_count > 1)
                                    <input type="hidden" name="goal_id1" value="{{ $course->goals[1]->id }}" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row mb-0">
                                <div class="col-md-4 offset-5">
                                    <button type="submit" name="revision" class="btn btn-danger">
                                        {{ __($btnText) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
