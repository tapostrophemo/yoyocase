class YoyosController < ApplicationController
  def new
    @yoyo = Yoyo.new
  end

  def create
    @yoyo = current_user.yoyos.create(params[:yoyo])
    if @yoyo.save
      save_photos
      flash[:msg] = "Yoyo added to collection successfully"
      redirect_to yoyo_url(@yoyo)
    else
      render :action => "new"
    end
  end

  def show
    @yoyo = Yoyo.find(params[:id])
  end

  def edit
    @yoyo = Yoyo.find(params[:id])
    unless current_user == @yoyo.user
      flash[:err] = "You cannot edit another user's yoyos"
      redirect_to yoyo_url(@yoyo)
    end
  end

  def update
    @yoyo = Yoyo.find(params[:id])
# TODO: figure out how to test this bit of security
    unless current_user == @yoyo.user
      flash[:err] = "You cannot update another user's yoyos"
      return redirect_to yoyo_url(@yoyo)
    end

    if @yoyo.update_attributes(params[:yoyo])
      save_photos
      flash[:msg] = "Yoyo saved successfully"
      redirect_to yoyo_url(@yoyo)
    else
      render :action => "edit"
    end
  end

  def remove_photo
    photo = Photo.find(params[:id])
    yoyo = photo.yoyo
    if current_user == yoyo.user
      photo.delete
      redirect_to edit_yoyo_path(yoyo.id)
    else
      flash[:err] = "You cannot remove photos from another user's yoyos"
      redirect_to account_url
    end
  end

  private

  def save_photos
    if params[:photos]
      params[:photos].each do |photo|
        @yoyo.photos << Photo.new({:url => photo})
      end
    end
  end
end
