class Yoyo < ActiveRecord::Base
  belongs_to :user
  has_many :photos

  validates_presence_of :model_name

  def first_photo
    if photos.length > 0
      photos.first.url
    else
      nil
    end
  end
end
