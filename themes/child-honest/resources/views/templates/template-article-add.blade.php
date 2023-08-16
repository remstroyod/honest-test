{{--
  Template Name: Add Article Page Template
--}}

@extends('layouts.app')

@section('content')

  <form action="{{ home_url() }}" class="form addArticle" id="addArticle" method="post" enctype="multipart/form-data">

    <div class="form__group">
      <label class="form__group-label" for="title">{{ __('Title', 'child-honest') }}</label>
      <input class="form__group-input" type="text" name="title" value="" id="title">
    </div>

    <div class="form__group">
      <label class="form__group-label" for="preview">{{ __('Image', 'child-honest') }}</label>
      <input class="form__group-input" type="file" name="preview" value="" id="preview">
    </div>

    <div class="form__group">
      <label class="form__group-label" for="email">{{ __('Email', 'child-honest') }}</label>
      <input class="form__group-input" type="email" name="email" value="" id="email">
    </div>

    <div class="form__group">
      <button type="submit" class="form__group-btn">{{ __('Send', 'child-honest') }}</button>
    </div>

    <div class="form__message"></div>

    <input type="hidden" name="action" value="add_article">

  </form>

@endsection
