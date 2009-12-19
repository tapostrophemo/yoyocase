class PagesController < ApplicationController

  def home
#    require 'flickr_fu'
#    flickr = Flickr.new("#{RAILS_ROOT}/config/flickr.yml")
#    @photos = flickr.photos.search(:tags => 'yoyocase')

    @photos = []
  end

  def credits
  end

end
